<?php

declare(strict_types=1);

namespace App\Services\DTO;

class Meeting
{
    public function __construct(
        public readonly int $id,
        public readonly \DateTime $changed,
        public readonly \DateTime $start,
        public readonly \DateTime $end,
        public readonly string $title,
        public readonly array $accepted,
        public readonly array $rejected,
    ) {
    }

    public static function fromArray(array $meeting): self
    {
        return new self(
            id: $meeting['id'],
            changed: new \DateTime($meeting['changed']),
            start: new \DateTime($meeting['start']),
            end: new \DateTime($meeting['end']),
            title: $meeting['title'],
            accepted: $meeting['accepted'] ?? [],
            rejected: $meeting['rejected'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'changed' => $this->changed,
            'start' => $this->start,
            'end' => $this->end,
            'title' => $this->title,
            'accepted' => $this->accepted,
            'rejected' => $this->rejected,
        ];
    }
}