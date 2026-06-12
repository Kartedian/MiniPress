import 'package:flutter/material.dart';
import 'screens/article_list_screen.dart';

void main() {
  runApp(const MiniPressApp());
}

class MiniPressApp extends StatelessWidget {
  const MiniPressApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'MiniPress',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.indigo),
        useMaterial3: true,
      ),
      home: const ArticleListScreen(),
    );
  }
}