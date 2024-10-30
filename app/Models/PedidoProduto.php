<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    use HasFactory;

    protected $fillable = ['Pedidos_id', 'Produtos_id', 'quantidade', 'observacao'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'Pedidos_id');
    }
}
?>