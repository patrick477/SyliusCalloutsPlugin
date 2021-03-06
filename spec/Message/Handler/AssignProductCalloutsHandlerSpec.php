<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutsPlugin\Message\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutsPlugin\Message\Command\AssignProductCallouts;
use Setono\SyliusCalloutsPlugin\Message\Handler\AssignProductCalloutsHandler;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutsPlugin\Provider\CalloutProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AssignProductCalloutsHandlerSpec extends ObjectBehavior
{
    function let(
        CalloutProviderInterface $calloutProvider,
        RepositoryInterface $productRepository,
        EntityManagerInterface $productManager
    ): void {
        $this->beConstructedWith($calloutProvider, $productRepository, $productManager);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AssignProductCalloutsHandler::class);
    }

    function it_executes(
        RepositoryInterface $productRepository,
        CalloutsAwareInterface $firstProduct,
        CalloutsAwareInterface $secondProduct,
        Collection $callouts,
        CalloutProviderInterface $calloutProvider,
        CalloutInterface $callout,
        EntityManagerInterface $productManager,
        AssignProductCallouts $assignProductCallouts
    ): void {
        $assignProductCallouts->getProductIds()->willReturn([1, 2]);
        $productRepository->findBy(['id' => [1, 2]])->willReturn([$firstProduct, $secondProduct]);

        $calloutProvider->getCallouts($firstProduct)->willReturn([$callout]);
        $calloutProvider->getCallouts($secondProduct)->willReturn([$callout]);

        $firstProduct->getCallouts()->willReturn($callouts);
        $secondProduct->getCallouts()->willReturn($callouts);

        $callouts->clear()->shouldBeCalled();
        $firstProduct->setCallouts(new ArrayCollection([$callout->getWrappedObject()]))->shouldBeCalled();
        $secondProduct->setCallouts(new ArrayCollection([$callout->getWrappedObject()]))->shouldBeCalled();
        $productManager->flush()->shouldBeCalled();

        $this->__invoke($assignProductCallouts);
    }
}
