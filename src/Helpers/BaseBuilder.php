<?php


namespace KMLaravel\ApiGenerator\Helpers;


use Illuminate\Support\Facades\File;

class BaseBuilder
{

    /**
     * @param $file
     * @param $path
     * @param $columns
     * @return bool
     */
    public static function replaceRulesWithRulesOptions($file, $path, $columns)
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
        static::replacement("//", $validationRow, $file, $path);
    }

    public function buildColumnInModelWithMigration($modelFileToCreate, $modelFilepath, $columns)
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
        static::replacement("//", $protectedFillable, $modelFileToCreate, $modelFilepath);
        $appMigrationPath = File::files(app_path("../database/migrations"));
        $fileMigrationName = $appMigrationPath[array_key_last($appMigrationPath)]->getRelativePathname();
        $fileMigrationPath = app_path("../database/migrations/$fileMigrationName");
        $fileMigrationPathAsStream = File::get($fileMigrationPath);
        static::replacement('$table->id();', $databaseColumn, $fileMigrationPathAsStream, $fileMigrationPath);

    }

    public static function replacement($searchFor, $replacementWith, $file, $path)
    {
        $newFile = str_replace($searchFor, $replacementWith, $file);
        file_put_contents($path, $newFile);
    }
}
