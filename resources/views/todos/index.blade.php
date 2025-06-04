<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>TODOリスト</title>
    <style>
        .completed {
            text-decoration: line-through;
            color: #888;
        }
    </style>
</head>
<body>
<h1>TODOリスト</h1>
<p>現在時刻：{{ now()->format('Y年m月d日 H:i') }}</p>

<button onclick="document.getElementById('modal').style.display='block'">タスク追加</button>

<div id="modal" style="display:none; background:#fff; border:1px solid #aaa; padding:20px; position:absolute; top:50px;">
    <form action="/todos" method="POST">
        @csrf
        <label>タイトル:</label>
        <input type="text" name="title">
        <button type="submit">登録</button>
        <button type="button" onclick="document.getElementById('modal').style.display='none'">閉じる</button>
    </form>
</div>

<ul style="list-style: none; padding: 0;">
    @foreach ($todos as $todo)
        <li style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #ddd; padding: 10px 0;">
            
            <!-- ✅ 左：チェックボックスとタイトル -->
            <form action="/todos/{{ $todo->id }}/toggle" method="POST" style="display: flex; align-items: center; flex: 1;">
                @csrf
                @method('PATCH')
                <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $todo->completed ? 'checked' : '' }} style="margin-right: 8px;">
                <span class="{{ $todo->completed ? 'completed' : '' }}">{{ $todo->title }}</span>
            </form>

            <!-- ✅ 中央：日時（幅固定で右寄せ） -->
            <div style="width: 180px; text-align: right; font-size: 0.9em; color: #666;">
                {{ $todo->created_at->format('Y年m月d日 H:i') }}
            </div>

            <!-- ✅ 右：削除ボタン -->
            <form action="/todos/{{ $todo->id }}" method="POST" style="margin-left: 16px;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('削除してもよろしいですか？')">削除</button>
            </form>

        </li>
    @endforeach
</ul>
</body>
</html>
