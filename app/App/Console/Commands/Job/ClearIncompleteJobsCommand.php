<?php

namespace Rims\App\Console\Commands;

use Illuminate\Console\Command;
use Rims\Domain\Jobs\Models\Job;

class ClearIncompleteJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:delete-incomplete {day=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes incomplete job listings older than a day or more';

    /**
     * The Job model instance.
     *
     * @var Job $job
     */
    protected $job;

    /**
     * Create a new command instance.
     *
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        parent::__construct();

        $this->job = $job;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = $this->argument('day');

        try {
            $count = $this->job->incomplete($days)->forceDelete();

            $this->info(sprintf('%s incomplete job listings deleted.', $count));
        } catch (\Exception $exception) {
            $this->error(sprintf('Failed to delete incomplete listings \n %s', $exception->getMessage()));
        }
    }
}
