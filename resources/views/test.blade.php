<!DOCTYPE html>
<html>
<head>
    <title>Test CSRF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test CSRF</h1>
    
    <form id="testForm" action="{{ route('test.post') }}" method="POST">
        @csrf
        <input type="text" name="test_field" value="test value">
        <button type="submit">Tester</button>
    </form>

    <div id="result"></div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    test_field: document.querySelector('input[name="test_field"]').value,
                    _token: document.querySelector('input[name="_token"]').value
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('result').innerHTML = 'Erreur: ' + error.message;
            });
        });
    </script>
</body>
</html>
