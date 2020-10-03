<?php


namespace KMLaravel\ApiGenerator\BuildClasses;

use Illuminate\Support\Facades\File;
use KMLaravel\ApiGenerator\Helpers\KMFileHelper;

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
     *
     */
    public function updatePaths($options = [], $file = null, $fileAsStream = null): KMBaseBuilder
    {
        // check if user dose not insert any custom files paths
        if (is_null($file) && is_null($file)) {
            $file = $this->helperFileToGet[$this->functionToBuild][0];
            $fileAsStream = $this->helperFileToGet[$this->functionToBuild][1];
        }
        // get this fil according whatever function we use from KMFileHelper facade
        $this->filepath = KMFileHelper::$file("$this->fileName.php");
        // get this fil according whatever function we use from KMFileHelper facade as stream to read it and doing replacement process
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
            // if user dose not choose any validations rules the default  rule will be sometimes
            // this allow Request Class to see fields
            foreach ($item['validation'] ?? ['sometimes'] as $rule) {
                $validation[] = $rule;
            }
            // we combine rules here like "name" => "required|min:1"
            $rulesAsString = implode("|", $validation);
            $validationRow .= "'$name' =>  '$rulesAsString',\n";
            $validation = [];
        }
        static::replacement("//", $validationRow, $options["file"], $options["path"]);
    }

    /**
     * @param $columns
     * @param array $options
     * the main job is to replace fillabe property in model
     * and column with type in migration file
     */
    public function modelAndMigrationReplacer($columns, $options = [])
    {
        // model area
        $fillable = [];
        $protectedFillable = "";
        $databaseColumn = '$table->id();' . "\n";
        // we build here fillabel for model and column for migration file at once.
        foreach ($columns as $name => $item) {
            // build fillabe property in model.
            array_push($fillable, "'$name'");
            // get column type.
            $type = array_key_first($item['type']);
            // put this column as this ex : $table->string('name').
            $databaseColumn .= '$table->' . "$type('$name'); \n";
        }
        $imp = implode(",", $fillable);
        $protectedFillable .= 'protected $fillable = ' . " [$imp];";
        static::replacement("//", $protectedFillable, $options["file"], $options["path"]);
        // check if user choose to build migration.
        if (isset($options['migrationRequired'])) {
            // get all migrations files.
            $appMigrationPath = File::files(app_path("../database/migrations"));
            // get last migration file we create from second steps when we call artisan.
            $fileMigrationName = $appMigrationPath[array_key_last($appMigrationPath)]->getRelativePathname();
            // get final path.
            $fileMigrationPath = app_path("../database/migrations/$fileMigrationName");
            // open and read this migration file.
            $fileMigrationPathAsStream = File::get($fileMigrationPath);
            // now put columns inside file.
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
        // update path after we build file name and whatever function we want to run.
        $this->updatePaths();
        // determine this function to run.
        $functionToBuild = $this->functionToBuild;
        // run this function.
        $this->$functionToBuild($column, array_merge([
            "file" => $this->fileToCreate, "path" => $this->filepath
        ], $options));
        // return final file path we process.
        return $this->filepath;
    }
}
