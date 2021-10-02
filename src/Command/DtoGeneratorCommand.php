<?php

declare(strict_types=1);

namespace App\Command;

use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\PhpCodeBuilder\JsonSchema\ClassHookCallback;
use Swaggest\PhpCodeBuilder\JsonSchema\PhpBuilder;
use Swaggest\PhpCodeBuilder\PhpClass;
use Swaggest\PhpCodeBuilder\PhpCode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Swaggest\PhpCodeBuilder\App\PhpApp;
use Swaggest\JsonSchema\Schema;

class DtoGeneratorCommand extends Command
{
    protected static $defaultName = 'schema:generate-dto';

    protected function configure()
    {
        $this
            ->setDescription('Generate DTO for JSON schema')
            ->addArgument('schema-path', InputArgument::REQUIRED, 'JSON schema relative path')
            ->addArgument('dto-name', InputArgument::REQUIRED, 'DTO name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $file = $input->getArgument('schema-path');
        $dtoName = $input->getArgument('dto-name');

        if (substr($file, 0, 1) !== DIRECTORY_SEPARATOR) {
            $file = getcwd() . DIRECTORY_SEPARATOR . $file;
        }

        if (!\is_file($file) || !\is_readable($file)) {
            $style->error('Invalid file');
            return -1;
        }

        $schemaData = \json_decode(\file_get_contents($file));

        $swaggerSchema = Schema::import($schemaData);

        $outputPath = dirname(__FILE__)
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'Data'
            . DIRECTORY_SEPARATOR
            . 'Schema'
            . DIRECTORY_SEPARATOR
            . $dtoName;

        if (\file_exists($outputPath)) {
            $style->warning('Path already exists');
            return -1;
        }

        $dtoNS = "App\\Data\\Schema\\" . $dtoName;

        $app = new PhpApp();
        $app->setNamespaceRoot($dtoNS, '.');

        $builder = new PhpBuilder();
        $builder->buildSetters = true;
        $builder->makeEnumConstants = true;

        $builder->classCreatedHook = new ClassHookCallback(
            function (PhpClass $class, $path, $schema) use ($app, $dtoNS) {
                $desc = '';

                if ($schema->title) {
                    $desc = $schema->title;
                }

                if ($schema->description) {
                    $desc .= "\n" . $schema->description;
                }

                $fromRefs = $schema->getFromRefs();

                if ($fromRefs) {
                    $desc .= "\nBuilt from " . implode("\n" . ' <- ', $fromRefs);
                }

                $desc .= "phpcs:ignorefile"
                    . PHP_EOL
                    . "@SuppressWarnings(PHPMD)"
                    . PHP_EOL;

                $class->setDescription(trim($desc));

                $class->setNamespace($dtoNS);

                if ('#' === $path) {
                    $class->setName('User'); // Class name for root schema
                } elseif (strpos($path, '#/definitions/') === 0) {
                    $class->setName(PhpCode::makePhpClassName(
                        substr($path, strlen('#/definitions/'))
                    ));
                }

                $app->addClass($class);
            }
        );

        $builder->getType($swaggerSchema);

        $app->clearOldFiles($outputPath);
        $app->store($outputPath);

        $style->success('Created class ' . $dtoNS . ' in ' . $outputPath);

        return 0;
    }
}
