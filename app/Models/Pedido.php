<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['Users_id', 'Enderecos_id', 'status'];

    public function produtos()
    {
        return $this->hasMany(PedidoProduto::class, 'Pedidos_id');
    }
}










