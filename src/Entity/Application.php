<?php

namespace App\Entity;

use App\Enum\ApplicationStatus;
use App\Repository\ApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['application:read'])]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['application:read'])]
    #[Assert\NotBlank]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    #[Groups(['application:read'])]
    #[Assert\NotBlank]
    private ?string $position = null;

    #[ORM\Column(enumType: ApplicationStatus::class)]
    #[Groups(['application:read'])]
    private ApplicationStatus $status = ApplicationStatus::APPLIED;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['application:read'])]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['application:read'])]
    #[Assert\Positive]
    private ?int $salaryExpectation = null;

    #[ORM\Column]
    #[Groups(['application:read'])]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $appliedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['application:read'])]
    private ?string $notes = null;

    #[ORM\Column]
    #[Groups(['application:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getStatus(): ApplicationStatus
    {
        return $this->status;
    }
    public function setStatus(ApplicationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getSalaryExpectation(): ?int
    {
        return $this->salaryExpectation;
    }

    public function setSalaryExpectation(?int $salaryExpectation): static
    {
        $this->salaryExpectation = $salaryExpectation;

        return $this;
    }

    public function getAppliedAt(): ?\DateTimeImmutable
    {
        return $this->appliedAt;
    }

    public function setAppliedAt(\DateTimeImmutable $appliedAt): static
    {
        $this->appliedAt = $appliedAt;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->notes;
    }

    public function setNote(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
