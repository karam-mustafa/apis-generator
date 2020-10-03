<?php


namespace KMLaravel\ApiGenerator\BuildClasses;


use Illuminate\Support\Facades\File;
use KMLaravel\ApiGenerator\Helpers\KMFileHelper;
use ReflectionClass;
use ReflectionException;

class KMBaseBuilder
{
    protected $fileName;
    protected $filepath;
    protected $fileToCreate;
    protected $functionToBuild;
    protected $helperFileToGet = [
        "requestReplacer" => ["getRequestFile", "getRequestFileAsStream"],
        "modelAndMigrationReplacer" => ["getModelFile", "getModelFileAsStream"],
    ];

    /**
     * @param $fileName
     * @param $functionToBuild
     * @return $this
     */
    public function initialResource($fileName, $functionToBuild): KMBaseBuilder
    {
        $this->functionToBuild = $functionToBuild;
        $this->fileName = $fileName;
        return $this;
    }


    /**
     * @param array $options
     * @param null $file
     * @param null $fileAsStream
     * @return $this
     */
    public function updatePaths($options = [], $file = null, $fileAsStream = null): KMBaseBuilder
    {
        if (is_null($file) && is_null($file)) {
            $file = $this->helperFileToGet[$this->functionToBuild][0];
            $fileAsStream = $this->helperFileToGet[$this->functionToBuild][1];
        }
        $this->filepath = KMFileHelper::$file("$this->fileName.php");
        $this->fileToCreate = KMFileHelper::$fileAsStream("$this->fileName.php");
        return $this;
    }

    /**
     * @param $columns
     * @param array $options
     * @return void
     */
    public static function requestReplacer($columns, $options = [])
    {
        $validation = [];
        $validationRow = null;
        foreach ($columns as $name => $item) {
            if (isset($item['validation'])) {
                foreach ($item['validation'] as $rule) {
                    $validation[] = $rule;
                }
                $rulesAsString = implode("|", $validation);
                $validationRow .= "'$name' =>  '$rulesAsString',\n";
                $validation = [];
            }
        }
        static::replacement("//", $validationRow, $options["file"], $options["path"]);
    }

    /**
     * @param $columns
     * @param array $options
     */
    public function modelAndMigrationReplacer($columns, $options = [])
    {
        // model area
        $fillable = [];
        $protectedFillable = "";
        $databaseColumn = '$table->id();' . "\n";
        foreach ($columns as $name => $item) {
            array_push($fillable, "'$name'");
            $type = array_key_first($item['type']);
            $databaseColumn .= '$table->' . "$type('$name'); \n";
        }
        $imp = implode(",", $fillable);
        $protectedFillable .= 'protected $fillable = ' . " [$imp];";
        static::replacement("//", $protectedFillable, $options["file"], $options["path"]);
        if (isset($options['migrationRequired'])){
            $appMigrationPath = File::files(app_path("../database/migrations"));
            $fileMigrationName = $appMigrationPath[array_key_last($appMigrationPath)]->getRelativePathname();
            $fileMigrationPath = app_path("../database/migrations/$fileMigrationName");
            $fileMigrationPathAsStream = File::get($fileMigrationPath);
            static::replacement('$table->id();', $databaseColumn, $fileMigrationPathAsStream, $fileMigrationPath);
        }
    }

    /**
     * @param $searchFor
     * @param $replacementWith
     * @param $file
     * @param $path
     */
    public static function replacement($searchFor, $replacementWith, $file, $path)
    {
        $newFile = str_replace($searchFor, $replacementWith, $file);
        file_put_contents($path, $newFile);
    }

    /**
     * @param $column
     * @param array $options
     * @return mixed
     */
    public function build($column, $options = [])
    {
        $this->updatePaths();
        $functionToBuild = $this->functionToBuild;
        $this->$functionToBuild($column, array_merge([
            "file" => $this->fileToCreate, "path" => $this->filepath
        ] , $options));
        return $this->filepath;
    }
}
