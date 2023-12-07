<?php
class Product
{
    private int $id;
    private string $name;
    private int $stan;
    private float $cena;
    private string $opis;
    private string $img;
    private string $opis2;
    private int $qty;

    public function __construct($id, $name, $cena, $opis,$img,$stan)
    {
        $this->id = $id;
        $this->name=$name;
        $this->cena = $cena;
        $this->opis = $opis;
        $this->img = $img;
        $this->stan=$stan;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getStan(): int
    {
        return $this->stan;
    }

    /**
     * @return float
     */
    public function getCena(): float
    {
        return $this->cena;
    }

    /**
     * @return string
     */
    public function getOpis(): string
    {
        return $this->opis;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @return string
     */
    public function getOpis2(): string
    {
        return $this->opis2;
    }


    function kup($n):void{
        $db=connect();
        $stan=$this->stan-$n;

            $result = $db->query("UPDATE produkty SET ilosc ='$stan' WHERE id = '$this->id';");
            if ($result)
                $this->stan=$stan;

    }

    /**
     * @param string $opis2
     */
    public function setOpis2(string $opis2): void
    {
        $this->opis2 = $opis2;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     */
    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }

}