<?php

class Book
{
    public int $BookID;
    public string $Title;
    public int $Author;
    public string $Description;
    public float $Price;
    public int $StockCount = 0;
    public string $ImagePath;
    public array $Genres = [];
}
