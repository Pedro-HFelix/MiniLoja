<?php
declare(strict_types=1);

class Product
{
    private string $code;
    private string $name;
    private int $price;
    private string $image;

    public function __construct(string $code, string $name, int $price, string $image)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
        ];
    }

    public static function fromArray(array $data): Product
    {
        if (!isset($data['code'], $data['name'], $data['price'])) {
            throw new InvalidArgumentException("Produto inválido");
        }

        return new Product(
            $data['code'],
            $data['name'],
            (int)$data['price'],
            $data['image']
        );
    }

    public static function loadAllFromJson(string $path): array
    {
        if (!file_exists($path)) {
            throw new RuntimeException("Arquivo não encontrado: $path");
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!$data) {
            throw new RuntimeException("Erro ao decodificar JSON: $path");
        }

        $products = [];

        foreach ($data as $item) {
            $product = Product::fromArray($item);
            $products[$product->getCode()] = $product;
        }

        return $products;
    }
}
