<?php

namespace Rampokker\CrudScaffold\Commands;

use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LaravelCrudGeneratorCommand extends Command
{
    public $signature = 'laravel_crud_generator';

    public $description = 'Creates CRUD scaffolding based on a series of templates for rapid application development';

    protected string $model;

    protected bool $isTenant;

    protected string $apiVersion;

    protected string $modelFields;

    public function handle(): int
    {
        $this->model = $this->ask('Model (singular)');
        $this->isTenant = $this->confirm('Should this model endpoint be accessible to tenants?', true);
        $this->modelFields = $this->ask('Provide a comma-seperated list of attributes for this model (eg. name,age,weight)', default: '');
        $this->apiVersion = $this->ask('API Version?', default: 'v1');

        $this->setMigration();
        $this->setFactory();
        $this->setRoute();
        $this->setController();
        $this->setModel();
        $this->setPolicies();
        $this->setPolicyInterfaces();
        $this->setStoreRequest();
        $this->setUpdateRequest();
        $this->setRepositoryEloquent();
        $this->setRepositoryInterface();
        $this->setResources();
        $this->setRolePermissions();
        // $this->setDocumentationEndpoints();
        // $this->setDocumentationSchema();
        $this->setFeatureTest();
        $this->setLanguage();

        return self::SUCCESS;
    }

    /**
     * @return void
     */
    private function setMigration()
    {
        Artisan::call('make:migration create_'.lcfirst($this->model).'s_table');
    }

    /**
     * @return void
     */
    private function setFactory()
    {
        $this->processTemplate('database/factories/', 'Factory.php', null);
    }

    /**
     * @return void
     */
    private function setRoute()
    {
        $centralRoutePath = '/routes/central/';
        $centralSubToken = '        require base_path() . \'/routes/';

        $tenantRoutePath = '/routes/';
        $tenantSubToken = "            require '";
        $routeMarker = "        });\n    });";

        if (! $this->isTenant) {
            $this->setRouteDetails($centralRoutePath, $routeMarker, $centralSubToken);
        } else {
            $this->setRouteDetails($tenantRoutePath, $routeMarker, $tenantSubToken);
        }

    }

    /**
     * @return void
     */
    private function setRouteDetails($routePath, $marker, $subToken)
    {
        echo "setting route\n";
        $routeFileName = lcfirst(Str::snake($this->model, '-')).'s.php';
        // naming the route template 's.template' fakes pluralization and allows usage of $this->processTemplate without cumbersome checks and conditionals
        $this->processTemplate($routePath, 's.php', null);

        File::move(base_path($routePath.ucfirst($this->model).'s.php'), base_path($routePath.$routeFileName));
        $routeFile = file_get_contents(base_path().$routePath.'api_'.$this->apiVersion.'.php');
        $template = str_replace($marker, $subToken.$routeFileName."';\n".$marker, $routeFile);
        file_put_contents(base_path().$routePath.'api_'.$this->apiVersion.'.php', $template);
    }

    /**
     * @return void
     */
    private function setController()
    {
        $this->processTemplate('app/Http/Controllers/', 'Controller.php', '/api/'.$this->apiVersion);
    }

    /**
     * @return void
     */
    private function setModel()
    {
        $paramsArray = array_map('trim', explode(',', $this->modelFields));
        $modelFields = "        '".implode("',\n        '", $paramsArray)."'";
        $this->processTemplate('app/Models/', '.php', null, ['key' => '[ModelFields]', 'value' => $modelFields]);
    }

    /**
     * @return void
     */
    private function setPolicies()
    {
        $this->processTemplate('app/Policies/', 'Policy.php', null);
    }

    /**
     * @return void
     */
    private function setPolicyInterfaces()
    {
        $this->processTemplate('app/Policies/Interfaces/', 'PolicyInterface.php', null);
    }

    /**
     * @return void
     */
    private function setStoreRequest()
    {
        $this->processTemplate('app/Http/Requests/', 'StoreRequest.php', null);
    }

    /**
     * @return void
     */
    private function setUpdateRequest()
    {
        $this->processTemplate('app/Http/Requests/', 'UpdateRequest.php', null);
    }

    /**
     * @return void
     */
    private function setRepositoryInterface()
    {
        $this->processTemplate('app/Http/Repository/', 'RepositoryInterface.php', '/api/'.$this->apiVersion);
    }

    //    /**
    //     * @return void
    //     */
    //    private function setDocumentationEndpoints()
    //    {
    //        $this->processTemplate('resources/yml/', 's.endpoints.yml', '/api/'.$this->apiVersion);
    //        File::makeDirectory(base_path('resources/yml/api/'.$this->apiVersion.'/'.lcfirst($this->model).'s'));
    //        File::move(base_path('resources/yml/api/'.$this->apiVersion.'/'.ucfirst($this->model).'s.endpoints.yml'),
    //            base_path('resources/yml/api/'.$this->apiVersion.'/'.lcfirst($this->model).'s/'.lcfirst($this->model).'s.endpoints.yml'));
    //    }

    /**
     * @return void
     */
    private function setFeatureTest()
    {
        $this->processTemplate('tests/Feature/', 'Test.php', null);
    }

    /**
     * @return void
     */
    private function setLanguage()
    {
        $this->processTemplate('resources/lang/en/', 's.php', null);
        File::move(base_path('resources/lang/en/'.ucfirst($this->model).'s.php'), base_path('resources/lang/en/'.lcfirst($this->model).'s.php'));
    }

    /**
     * @return void
     */
    private function setResources()
    {
        $paramsArray = array_map('trim', explode(',', $this->modelFields));
        array_walk($paramsArray, function (&$value, $key) {
            $value = "'$value' => ".'$this->'.$value;
        });
        $paramsArray = implode(",\n            ", $paramsArray);
        $this->processTemplate('app/Http/Resources/', 'Resource.php', '/api/'.$this->apiVersion, ['key' => '[ModelFields]', 'value' => $paramsArray]);
    }

    /**
     * @return void
     */
    private function setRolePermissions()
    {
        $marker = "]\n";
        $pathToPermissionsConfig = base_path().'/config/role_permissions.php';
        $permissionsConfig = file_get_contents($pathToPermissionsConfig);
        $permissionsTemplate = $this->getTemplate('config/role_permissions.php');
        $permissionsTemplate = str_replace('[modelName]', lcfirst($this->model), $permissionsTemplate);

        $template = str_replace($marker, "],\n".$permissionsTemplate."\n", $permissionsConfig);
        file_put_contents($pathToPermissionsConfig, $template);
    }

    /**
     * @return void
     */
    private function setRepositoryEloquent()
    {
        $template = $this->getTemplate('app/Http/Repository/Eloquent/Repository.php');
        $template = $this->doTokenReplacement($template, false);
        $this->putTemplate($template, 'app/Http/Repository/api/'.$this->apiVersion.'/Eloquent/'.ucfirst($this->model).'Repository.php');
    }

    //    /**
    //     * @return void
    //     */
    //    private function setDocumentationSchema()
    //    {
    //        $template = $this->getTemplate('resources/yml/schemas/s.schema.yml');
    //        $template = $this->doTokenReplacement($template, false);
    //        $this->putTemplate($template, 'resources/yml/api/'.$this->apiVersion.'/schemas/'.lcfirst($this->model).'s.schema.yml');
    //    }

    /**
     * @return void
     */
    private function processTemplate($templatePath, $templateName, $apiVersion, $additionalParams = false)
    {
        $template = $this->getTemplate($templatePath.$templateName);
        $template = $this->doTokenReplacement($template, $additionalParams);
        $this->putTemplate($template, $templatePath.$apiVersion.'/'.ucfirst($this->model).$templateName);
    }

    /**
     * @return array|string|string[]
     */
    private function doTokenReplacement($template, $additionalParams)
    {
        $template = str_replace('[ModelName]', ucfirst($this->model), $template);
        $template = str_replace('[modelName]', lcfirst($this->model), $template);
        $template = str_replace('[model-name]', lcfirst(Str::snake($this->model, '-')), $template);
        $template = str_replace('[apiVersion]', $this->apiVersion, $template);
        if ($additionalParams) {
            $template = str_replace($additionalParams['key'], $additionalParams['value'], $template);
        }

        return $template;
    }

    /**
     * @return false|string
     */
    private function getTemplate($path)
    {
        echo 'fetching template from '.__DIR__.'/../../resources/templates/'.$path."\n";

        return file_get_contents(__DIR__.'/../../resources/templates/'.$path);
    }

    /**
     * @return void
     */
    private function putTemplate($content, $path)
    {
        echo 'writing updated template to '.base_path($path)."\n\n";

        file_put_contents(base_path($path), $content);
    }
}
