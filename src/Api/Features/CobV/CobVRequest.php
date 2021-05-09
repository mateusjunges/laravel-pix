<?php

namespace Junges\Pix\Api\Features\CobV;

use Illuminate\Contracts\Support\Arrayable;

class CobVRequest implements Arrayable
{
    private string $dueDate;
    private int $validAfterDueDate;
    private int $loc;
    private string $debtorStreet;
    private string $debtorCity;
    private string $debtorUf;
    private string $debtorCpf;
    private string $debtorCnpj;
    private string $amount;

    public function toArray(): array
    {
        return [

        ];
    }
}