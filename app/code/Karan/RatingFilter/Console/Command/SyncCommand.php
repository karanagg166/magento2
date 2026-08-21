<?php
declare(strict_types=1);

namespace Karan\RatingFilter\Console\Command;

use Karan\RatingFilter\Model\RatingSync;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * bin/magento karan:rating-filter:sync
 *
 * Fills the rating_filter attribute from the reviews that already exist. Run it once after
 * installing the module; new reviews are picked up automatically after that.
 */
class SyncCommand extends Command
{
    /**
     * @var RatingSync
     */
    private $ratingSync;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param RatingSync $ratingSync
     * @param State $appState
     * @param string|null $name
     */
    public function __construct(RatingSync $ratingSync, State $appState, ?string $name = null)
    {
        $this->ratingSync = $ratingSync;
        $this->appState = $appState;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('karan:rating-filter:sync')
            ->setDescription('Rebuild the rating_filter product attribute from the review data');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);

        try {
            $updated = $this->ratingSync->sync();
        } catch (\Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }

        $output->writeln(sprintf('<info>Done, %d product(s) updated.</info>', $updated));

        return Command::SUCCESS;
    }
}
