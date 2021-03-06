<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Command;

use Setono\SyliusCalloutsPlugin\Assigner\ProductCalloutsAssignerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AssignProductCalloutsCommand extends Command
{
    /** @var ProductCalloutsAssignerInterface */
    private $productCalloutsAssigner;

    public function __construct(ProductCalloutsAssignerInterface $productCalloutsAssigner)
    {
        parent::__construct();

        $this->productCalloutsAssigner = $productCalloutsAssigner;
    }

    protected function configure(): void
    {
        $this
            ->setName('setono:callouts:assign')
            ->setDescription('Assigns callouts to products.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->productCalloutsAssigner->assign();
    }
}
