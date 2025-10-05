<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Service\CreditService;
use App\Service\BookingService;
use App\Entity\User;
use App\Entity\Trip;

class BookingServiceTest extends TestCase
{
    private $bookingService;
    private $user;
    private $trip;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject */
        $mockEm = $this->createMock(EntityManagerInterface::class);
        /** @var UserRepository&\PHPUnit\Framework\MockObject\MockObject */
        $mockUserRepo = $this->createMock(UserRepository::class);
        /** @var CreditService&\PHPUnit\Framework\MockObject\MockObject */
        $mockCreditService = $this->createMock(CreditService::class);

        $this->bookingService = new BookingService($mockEm, $mockUserRepo, $mockCreditService);
        $this->user = new User();
        $this->trip = new Trip();
    }

    public function testCanUserBookWithNotEnoughCredits()
    {
        $this->user->setCredit(0);
        $this->trip->setPrice(10);
        $this->trip->setSeatsAvailable(1);

        $result = $this->bookingService->canUserBook($this->user, $this->trip);
        $this->assertSame("Vous n'avez pas assez de crédits.", $result);
    }

    public function testCanUserBookTripIsFull()
    {
        $this->user->setCredit(100);
        $this->trip->setPrice(10);
        $this->trip->setSeatsAvailable(0);

        $result = $this->bookingService->canUserBook($this->user, $this->trip);
        $this->assertSame("ce trajet est complet.", $result);
    }

    public function testCanUserBookSuccess()
    {
        $this->user->setCredit(100);
        $this->trip->setPrice(10);
        $this->trip->setSeatsAvailable(1);

        $result = $this->bookingService->canUserBook($this->user, $this->trip);
        $this->assertNull($result);
    }

}
