<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-domain {name} {--wsf}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new domain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $with_service_function = $this->option('wsf');

        $this->createServiceContract($name, $with_service_function);
        $this->createServiceImplementation($name, $with_service_function);
        if ($with_service_function)
            $this->call('make:model', [
                'name' => $name,
                '--migration' => true
            ]);
    }

    protected function createServiceContract($name, $wsf)
    {
        $contract_name = $name . 'Service';
        $directory = app_path("Services\\DomainServices");
        $path = $directory . '\\' . $contract_name . '.php';
        $namespace = 'App\\Services\\DomainServices';
        if (file_exists($path)) {
            $this->error($contract_name . ' already exists!');
            return;
        }
        $methods = [];
        $uses = [];
        if ($wsf) {
            $methods = [
                [
                    'name' => 'save(array $data_' . strtolower($name) . '): ?' . $name,
                ],
                [
                    'name' => 'find(int $id): ?' . $name,
                ],
                [
                    'name' => 'update(int $id, array $data_' .strtolower($name) . '): ?' . $name,
                ],
                [
                    'name' => 'delete(int $id): bool'
                ]
            ];
            $uses = [
                'use App\\Models\\' . $name . ';',
            ];
        }
        $class_template = $this->generateClassTemplate(false, false, 'interface', $contract_name, $namespace, $uses, $methods);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 755, true);
        }
        File::put($path, $class_template);
        $this->info("\n  <fg=white;bg=blue> INFO </> Service Contract [{$path}] created successfully\n");
    }

    protected function createServiceImplementation($name, $wsf)
    {
        $impl_name = $name . 'ServiceImpl';
        $directory = app_path("Services\\DomainServices\\Impl");
        $path = $directory . '\\' . $impl_name . '.php';
        $namespace = 'App\\Services\\DomainServices\\Impl';
        if (file_exists($path)) {
            $this->error($impl_name . ' already exists!');
            return;
        }
        $methods = [];
        $uses = [
            'use App\\Services\\DomainServices\\' . $name . 'Service;'
        ];
        if ($wsf) {
            $methods = [
                [
                    'name' => 'save(array $data_' . strtolower($name) . '): ?' . $name,
                    'return' => 'null'
                ],
                [
                    'name' => 'find(int $id): ?' . $name,
                    'return' => 'null'
                ],
                [
                    'name' => 'update(int $id, array $data_' .strtolower($name) . '): ?' . $name,
                    'return' => 'null'
                ],
                [
                    'name' => 'delete(int $id): bool',
                    'return' => 'false'
                ]
            ];
            $uses[] = 'use App\\Models\\' . $name . ';';
        }
        $class_full = $impl_name . ' implements ' . $name . 'Service';
        $class_template = $this->generateClassTemplate(true, true, 'class', $class_full, $namespace, $uses, $methods);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 755, true);
        }
        File::put($path, $class_template);
        $this->info("  <fg=white;bg=blue> INFO </> Service Implementation [{$path}] created successfully\n");
    }

    protected function generateClassTemplate(bool $use_visibility, bool $use_instansiate, $type, $name, $namespace, $uses, $methods)
    {
        $template_path = resource_path('views\stubs\class.stub');
        $template = File::get($template_path);

        $uses_template = "\n";
        foreach ($uses as $use) {
            $uses_template .= "{$use}\n";
        }
        $methods_template = '';
        foreach ($methods as $method) {
            $methods_template .= ($use_visibility ? 'public ' : '') . 'function ' . $method['name'] . ($use_instansiate ? "\n\t{\n\t\treturn " . $method['return'] . ";\n\t}\n\n\t" : ";\n\n\t");
        }

        $methods_template = strlen($methods_template) <= 0 ? "// function\n" : $methods_template;
        
        $template = str_replace(
            ['{{ namespace }}', '{{ uses }}', '{{ type }}', '{{ name }}', '{{ methods }}'],
            [$namespace, $uses_template, $type, $name, $methods_template],
            $template
        );

        return $template;
    }
    
}