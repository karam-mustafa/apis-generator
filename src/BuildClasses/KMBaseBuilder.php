<?php


namespace KMLaravel\ApiGenerator\BuildClasses;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KMLaravel\ApiGenerator\Facade\KMFunctionsFacade;
use KMLaravel\ApiGenerator\Helpers\KMFile;

class KMBaseBuilder
{
    /**
     * @var string
     */
    protected $fileName;
    /**
     * @var string
     */
    protected $filepath;
    /**
     * @var string|mixed
     */
    protected $fileToCreate;
    /**
     * @var string
     */
    protected $functionToBuild;
    /**
     * @var array
     */
    protected $helperFileToGet = [
        "requestReplacer" => ["getRequestFile", "getRequestFileAsStream"],
        "modelAndMigrationReplacer" => ["getModelFile", "getModelFileAsStream"],
    ];

    /**
     * @param string $fileName
     * @param string $functionToBuild
     * @return KMBaseBuilder
     */
    public function initialResource($fileName, $functionToBuild): KMBaseBuilder
    {
        $this->functionToBuild = $functionToBuild;
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @param string $option
     * @return KMBaseBuilder
     */
    public function callArtisan($option = ''): KMBaseBuilder
    {
        $command = strtolower($this->typeToMake);
        Artisan::call("make:$command $this->fileName $option");
        return $this;
    }

    /**
     * @param array $options
     * @param null|string $file
     * @param null|string $fileAsStream
     * @return KMBaseBuilder
     *
     */
    public function updatePaths($options = [], $file = null, $fileAsStream = null): KMBaseBuilder
    {
        // check if user dose not insert any custom files paths
        if (is_null($file) && is_null($fileAsStream)) {
            $file = $this->helperFileToGet[$this->functionToBuild][0];
            $fileAsStream = $this->helperFileToGet[$this->functionToBuild][1];
        }
        // get this fil according whatever function we use from KMFileFacade facade
        $this->filepath = KMFile::$file("$this->fileName.php");
        // get this fil according whatever function we use from KMFileFacade facade as stream to read it and doing replacement process
        $this->fileToCreate = KMFile::$fileAsStream("$this->fileName.php");
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @return false|int
     */
    public function requestReplacer($columns, $options = [])
    {
        file_put_contents($this->filepath, KMFile::getFilesFromStubsAsStream("Request"));
        $this->updatePaths();
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
        return $this->replacement(
            ["{{ request_namespace }}" , "{{ rules }}", "{{ request_class }}", "{{ request_auth }}"],
            [
                $this->checkIfFileInSubFolder("App\Http\Requests" , $this->fileName),
                $validationRow,
                $this->array_last_value(explode('/', $this->fileName)),
                KMFunctionsFacade::getRequestAuthAccessibility()
            ],
            $this->fileToCreate, $this->filepath);
    }

    /**
     * @param array $columns
     * @param array $options
     * the main job is to replace fillabe property in model
     * and column with type in migration file
     * @return false|int
     */
    public function modelAndMigrationReplacer($columns, $options = [])
    {
        // model area
        $fillable = [];
        $protectedFillable = "";
        // this step to support laravel 5.8
        $migrationId = app()->version() > 6 ? '$table->id();' : '$table->bigIncrements(\'id\');';
        $databaseColumn = $migrationId."\n";
        // we build here fillable for model and column for migration file at once.
        foreach ($columns as $name => $item) {
            // build fillable property in model.
            array_push($fillable, "'$name'");
            // get column type.
            $type = array_key_first($item['type']);
            // put this column as this ex : $table->string('name').
            $databaseColumn .= '$table->' . "$type('$name'); \n";
        }
        $imp = implode(",", $fillable);
        $protectedFillable .= 'protected $fillable = ' . " [$imp];";
        $this->replacement("//", $protectedFillable, $options["file"], $options["path"]);
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
            return $this->replacement($migrationId, $databaseColumn, $fileMigrationPathAsStream, $fileMigrationPath);
        }
    }

    /**
     * @param array $searchFor
     * @param array $replacementWith
     * @param string $file
     * @param string $path
     * @return false|int
     */
    public function replacement($searchFor, $replacementWith, $file, $path)
    {
        if (!gettype($searchFor) == 'array' && !gettype($replacementWith) == "array") {
            [$searchFor, $replacementWith] = [[$searchFor], [$replacementWith]];
        }
        $newFile = str_replace($searchFor, $replacementWith, $file);
        return file_put_contents($path, $newFile);
    }

    /**
     * @param array $column
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

    /**
     * @param array $array
     * @return mixed
     */
    protected function array_first_value($array)
    {
        return $array[0];
    }

    /**
     * @param array $array
     * @return mixed
     */
    protected function array_last_value($array = [])
    {
        return $array[array_key_last($array)];
    }

    /**
     * @param array | mixed $classNameSpace
     * @return mixed
     */
    protected function getClassName($classNameSpace)
    {
        $fullNameSpaceClass = explode('\\', $classNameSpace);
        $lastWord = array_key_last(explode('\\', $classNameSpace));
        return $this->array_first_value(explode('\\', $fullNameSpaceClass[$lastWord]));
    }

    /**
     * @param $initPath
     * @param $fileName
     * @return string
     */
    protected function checkIfFileInSubFolder($initPath , $fileName){
        $fileNameAsArray = explode('/' , $fileName);
        if (sizeof($fileNameAsArray) > 1)  return $this->ImplodeNameSpace($initPath , $fileNameAsArray);
        return $initPath;
    }

    /**
     * @param $initPath
     * @param $fileNameAsArray
     * @return string
     */
    protected function ImplodeNameSpace($initPath , $fileNameAsArray){
        array_pop($fileNameAsArray);
        return "$initPath\\".implode('\\' , $fileNameAsArray);
    }
}
