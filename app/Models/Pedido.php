<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['Users_id', 'Enderecos_id', 'status'];

    // Em App\Models\Pedido.php
// Em App\Models\Pedido.php
public function produtos()
{
    return $this->belongsToMany(Produto::class, 'pedido_produtos', 'Pedidos_id', 'Produtos_id')
                ->withPivot('quantidade', 'observacao');
}


    // Em App\Models\Pedido.php
public function endereco()
{
    return $this->belongsTo(Endereco::class, 'Enderecos_id');
}

}