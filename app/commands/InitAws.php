<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Aws\S3\S3Client;

class InitAws extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bestline:init-aws';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Initialize AWS Environment';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$s3Client = S3Client::factory(\Config::get('aws'));
		
		$this->info("Retrieivng S3 buckets");
		
		$results = $s3Client->listBuckets();
		
		$bucketName = "imagebucket-" . \App::environment();
		
		$exists = false;
		foreach($results['Buckets'] as $bucket) {
		    if($bucketName == $bucket['Name']) {
		        $exists = true;
		    }
		}
		
		if(!$exists) {
		    $this->info("Creating Image storage bucket $bucketName");
		    
		    $s3Client->createBucket([
		        'Bucket' => $bucketName
		    ]);
		    
		    $s3Client->waitUntil('BucketExists', [
		        'Bucket' => $bucketName
		    ]);
		    
		    $this->info("Image Storge bucket $bucketName created");
		} else {
		    $this->info("Skipping Image storage bucket creation $bucketName already exists");
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
