<?php

namespace Jose\Console;

use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreatePostType extends Command
{

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'create:post-type';

    protected $output;
    protected $input;
    protected $options;
    protected $fileName;

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Creates a new post type.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to create new post type.')

        // ...

   
        // configure an argument
        ->addArgument('name', InputArgument::REQUIRED, 'The name of the post type in singular.')
        ->addOption(
            'plural',
            'p',
            InputOption::VALUE_OPTIONAL,
            'You can pass the plural name of the post type (by default it will just add an s to the end',
            null
        );
        // ...

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    
    {
        $this->output = $output;
        $this->input = $input;
        $this->options = $this->input->getOptions();

        $output->writeln("<info>Creating post type ".$this->input->getArgument('name')."...</info>");

        try {
            $this->createPostType()
                ->registerPostType();
            $output->writeln("<info>Post type ".$this->input->getArgument('name')." created!</info>");
            return Command::SUCCESS;
        }  catch (LogicException  $e) {
            $output->writeln("<error>".$e->getMessage()."</error>");

            return Command::FAILURE;
        }
     
      
        // outputs multiple lines to the console (adding "\n" at the end of each line)

    }


    public function createPostType() {

        // Get the copntent of the template for post type
        $newFileContent = file_get_contents(TEMPLATES."/PostType.php");

        // Set the differente classe name
        $className = strtolower($this->input->getArgument('name'));
        $pluralClassName = $this->options["plural"] ? $this->options["plural"] : $className."s";

        // Replace class names in ther template fie
        $newFileContent = str_replace( "{!!SINGULAR_NAME!!}", ucfirst($className), $newFileContent);
        $newFileContent = str_replace( "{!!SINGULAR_LOWER_NAME!!}", $className, $newFileContent);
        $newFileContent = str_replace( "{!!PLURAL_NAME!!}",  ucfirst($pluralClassName), $newFileContent);
        $newFileContent = str_replace( "{!!PLURAL_LOWER_NAME!!}",  $pluralClassName, $newFileContent);
        //dd($file);

        // Copy the new content
        $filesystem = new Filesystem();
        $this->fileName = ucfirst($className)."PostType";
        $filePath = POST_TYPE."/".$this->fileName.".php";

        // If file already exist: exit
        if($filesystem->exists($filePath)) {
            throw new LogicException('Error, file already exist in '.$filePath.'!');
        }

        // Create the file and update it
        $filesystem->touch($filePath);
        file_put_contents($filePath, $newFileContent);
  
        return $this;
    }

    public function registerPostType() {

        $postTypeFile = ROOT.'/app/config/PostType/PostType.php';

        $contentPostTypeFile = file_get_contents($postTypeFile);

        // Check if the class is already declared
        if(strpos($contentPostTypeFile,  $this->fileName)) {
            throw new LogicException('Class '.$this->fileName.'  already declared in '.$postTypeFile.'!</error>');
        }

        // if not, get the line to insert the declaration
        $lines = array();
        foreach(file($postTypeFile) as $line)
        {
            if('// @End' == trim($line))
            {
                array_push($lines, '      new '.$this->fileName."();\n");
            }
            array_push($lines, $line);
        }

        // Erase it with the new content
        file_put_contents($postTypeFile, $lines);
        return $this;
    }

}
