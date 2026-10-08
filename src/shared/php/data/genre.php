<?php

readonly class Genre
{
    public function __construct(
        public string $name,
        public string $description
    ) {}
}
