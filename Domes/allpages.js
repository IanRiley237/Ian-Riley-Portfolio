var cart = [];
class Order
{
    id;
    name;
    price;
    size;
    count;

    constructor(id, name, price, size, count)
    {
        this.id = id;
        this.name = name;
        this.price = price;
        this.size = size;
        this.count = count;
        this.addToCart();
    }

    addToCart()
    {
        cart.push(this);
        console.log(cart);
    }

    getOrderCost()
    {
        return this.count * this.price;
    }
}

function getCartList()
{
    var list = "";
    var totalCost = 0;
    cart.forEach(function(hat, index){
        // Gunslinger (M) X 12 → $26.00
        list += "<button type = 'button' onclick = 'removeOrderFromCart(" + index + ")'>X</button> "
        list += hat.name + " (" + hat.size + ")  &#x2A2F " + hat.count + " → $" +  (hat.getOrderCost()).toFixed(2) + "<br>";
        totalCost += hat.getOrderCost();
    });
    list += "<br><span class = 'totalcost'>Total: $" +  totalCost.toFixed(2) + "</span>";
    return list;
};

function cartToString()
{
    var list = "";
    cart.forEach(function(hat){
        // Gunslinger (M) X 12 → $26.00
        list += hat.id + "," + hat.size + "," + hat.count + ";";
    });
    return list;
};

function removeOrderFromCart(index)
{
    cart.splice(index, 1);
    $('#cartList').html(getCartList());
    $("#cartcount").html(cart.length);
}