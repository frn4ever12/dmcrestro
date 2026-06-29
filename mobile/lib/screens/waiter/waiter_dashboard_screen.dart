import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class WaiterDashboardScreen extends StatelessWidget {
  const WaiterDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Waiter Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => context.go('/login'),
          ),
        ],
      ),
      body: const Center(
        child: Text('Waiter Dashboard - Coming Soon'),
      ),
    );
  }
}
