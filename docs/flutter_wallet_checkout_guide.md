# Flutter Wallet & Checkout Implementation Guide

## 🔗 Backend API Endpoints

| Feature            | Method | Endpoint             | Description                        |
| ------------------ | ------ | -------------------- | ---------------------------------- |
| Get Wallet Balance | GET    | `/api/wallet`        | Returns balance only               |
| Get Cart           | GET    | `/api/cart`          | Returns cart items + total_price   |
| Add to Cart        | POST   | `/api/cart/{bookId}` | Add book to cart                   |
| Remove from Cart   | DELETE | `/api/cart/{bookId}` | Remove book                        |
| Checkout           | POST   | `/api/cart/checkout` | Deducts from wallet, creates order |

> **Note:** Transaction history is admin-only via `/api/admin/users/{id}/wallet`

---

## 1️⃣ Wallet Provider (Balance Only)

```dart
class WalletProvider extends ChangeNotifier {
  double _balance = 0;

  double get balance => _balance;

  Future<void> loadWallet() async {
    final response = await dio.get('/api/wallet');
    _balance = response.data['data']['balance'].toDouble();
    notifyListeners();
  }
}
```

---

## 2️⃣ Wallet Screen UI (Simple Balance)

```dart
class WalletScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<WalletProvider>(
      builder: (context, wallet, child) {
        return Scaffold(
          appBar: AppBar(title: Text('My Wallet')),
          body: Center(
            child: Container(
              padding: EdgeInsets.all(32),
              margin: EdgeInsets.all(16),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [Colors.purple, Colors.blue],
                ),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.account_balance_wallet,
                       size: 48, color: Colors.white),
                  SizedBox(height: 16),
                  Text('Balance',
                       style: TextStyle(color: Colors.white70, fontSize: 16)),
                  SizedBox(height: 8),
                  Text(
                    '\$${wallet.balance.toStringAsFixed(2)}',
                    style: TextStyle(
                      fontSize: 42,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
```

---

## 3️⃣ Cart Provider with Checkout

```dart
class CartProvider extends ChangeNotifier {
  List<Book> _items = [];
  double _totalPrice = 0;

  List<Book> get items => _items;
  double get totalPrice => _totalPrice;

  Future<void> loadCart() async {
    final response = await dio.get('/api/cart');
    final data = response.data['data'];
    _items = (data['cart']['items'] as List)
        .map((i) => Book.fromJson(i['book']))
        .toList();
    _totalPrice = data['total_price'].toDouble();
    notifyListeners();
  }

  Future<void> addToCart(int bookId) async {
    await dio.post('/api/cart/$bookId');
    await loadCart();
  }

  Future<void> removeFromCart(int bookId) async {
    await dio.delete('/api/cart/$bookId');
    await loadCart();
  }

  Future<CheckoutResult> checkout(WalletProvider wallet) async {
    try {
      final response = await dio.post('/api/cart/checkout');
      await loadCart();
      await wallet.loadWallet();
      return CheckoutResult(
        success: true,
        message: 'Order placed!',
        newBalance: response.data['data']['new_balance'],
      );
    } on DioException catch (e) {
      if (e.response?.statusCode == 400) {
        final data = e.response?.data['data'];
        return CheckoutResult(
          success: false,
          message: 'Insufficient balance',
          shortage: data?['shortage'],
        );
      }
      rethrow;
    }
  }
}

class CheckoutResult {
  final bool success;
  final String message;
  final double? newBalance;
  final double? shortage;

  CheckoutResult({
    required this.success,
    required this.message,
    this.newBalance,
    this.shortage,
  });
}
```

---

## 4️⃣ Checkout Screen

```dart
class CheckoutScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final cart = context.watch<CartProvider>();
    final wallet = context.watch<WalletProvider>();

    return Scaffold(
      appBar: AppBar(title: Text('Checkout')),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: cart.items.length,
              itemBuilder: (ctx, i) {
                final book = cart.items[i];
                return ListTile(
                  leading: Image.network(book.coverUrl ?? ''),
                  title: Text(book.title),
                  trailing: Text('\$${book.price.toStringAsFixed(2)}'),
                );
              },
            ),
          ),

          Container(
            padding: EdgeInsets.all(16),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Total:', style: TextStyle(fontSize: 18)),
                    Text('\$${cart.totalPrice.toStringAsFixed(2)}',
                      style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  ],
                ),
                SizedBox(height: 8),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Wallet:'),
                    Text('\$${wallet.balance.toStringAsFixed(2)}',
                      style: TextStyle(
                        color: wallet.balance >= cart.totalPrice
                            ? Colors.green : Colors.red)),
                  ],
                ),
                SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: wallet.balance >= cart.totalPrice
                        ? () => _checkout(context) : null,
                    child: Text(wallet.balance >= cart.totalPrice
                        ? 'Pay \$${cart.totalPrice}' : 'Insufficient Balance'),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _checkout(BuildContext context) async {
    final cart = context.read<CartProvider>();
    final wallet = context.read<WalletProvider>();
    final result = await cart.checkout(wallet);

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(result.message),
        backgroundColor: result.success ? Colors.green : Colors.red),
    );

    if (result.success) {
      Navigator.pushReplacementNamed(context, '/library');
    }
  }
}
```

---

## 5️⃣ Book Price Badge

```dart
Container(
  padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
  decoration: BoxDecoration(
    color: book.price == 0 ? Colors.green : Colors.blue,
    borderRadius: BorderRadius.circular(12),
  ),
  child: Text(
    book.price == 0 ? 'FREE' : '\$${book.price.toStringAsFixed(2)}',
    style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
  ),
)
```

---

## ✅ Checklist

-   [ ] Create WalletProvider (balance only)
-   [ ] Build Wallet Screen (simple balance card)
-   [ ] Update CartProvider with totalPrice
-   [ ] Build Checkout Screen
-   [ ] Show price badge on book cards (FREE or $X.XX)
-   [ ] Handle insufficient balance error
