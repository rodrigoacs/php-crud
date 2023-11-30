<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    a {
      margin-bottom: 1rem;
      text-align: center;
      width: 50%;
    }

    input {
      width: 100%;
      background: none;
    }

    table,
    th,
    td {
      border: 1px solid black;
      border-collapse: collapse;
    }
  </style>
  <title>Vendas</title>
</head>

<body>
  <a href="/index.php">cadastrar item</a>
  <table>
    <thead>
      <tr>
        <th>codigo</th>
        <th>descrição</th>
        <th>marca</th>
        <th>preço</th>
        <th>quantidade</th>
        <th>total</th>
      </tr>
    </thead>
    <tbody id="tableBody">
      <!-- Linhas de exemplo -->
      <tr>
        <td><input type="text" placeholder="codigo" class="codigo"></td>
        <td><input type="text" placeholder="descrição" class="descricao"></td>
        <td><input type="text" placeholder="marca" class="marca"></td>
        <td><input type="text" placeholder="preço" class="preco"></td>
        <td><input type="text" placeholder="quantidade" class="quantidade"></td>
        <td><input type="text" placeholder="total" class="total"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5">valor total: </td>
        <td><input type="number" placeholder="valor total" id="valor_total"></td>
      </tr>
    </tfoot>
    <button id="addItem">adicionar item</button>
  </table>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tableBody = document.getElementById('tableBody');
      const valorTotal = document.getElementById('valor_total');
      const addItemButton = document.getElementById('addItem');

      // Adiciona um listener para o botão de adicionar item
      addItemButton.addEventListener('click', addRow);

      // Função para adicionar uma nova linha à tabela
      function addRow() {
        const newRow = document.createElement('tr');

        const columns = ['codigo', 'descricao', 'marca', 'preco', 'quantidade', 'total'];

        columns.forEach(column => {
          const cell = document.createElement('td');
          const input = document.createElement('input');
          input.type = column === 'codigo' || column === 'descricao' || column === 'marca' ? 'text' : 'number';
          input.placeholder = column;
          input.classList.add(column);
          cell.appendChild(input);
          newRow.appendChild(cell);
        });

        tableBody.appendChild(newRow);

        // Adiciona um listener para cada novo input de código
        const codigoInputs = document.querySelectorAll('.codigo');
        codigoInputs.forEach(input => {
          input.addEventListener('keyup', handleCodigoInput);
        });
      }

      // Adiciona linhas de exemplo
      addRow();
      addRow();

      // Adiciona um listener para calcular o valor total
      tableBody.addEventListener('keyup', (event) => {
        const target = event.target;
        if (target.classList.contains('preco') || target.classList.contains('quantidade')) {
          updateTotal();
        }
      });

      // Função para calcular e atualizar o valor total
      function updateTotal() {
        const totalInputs = document.querySelectorAll('.total');
        let totalValue = 0;

        totalInputs.forEach(input => {
          const preco = input.parentElement.parentElement.querySelector('.preco').value || 0;
          const quantidade = input.parentElement.parentElement.querySelector('.quantidade').value || 0;
          const total = preco * quantidade;
          input.value = total;
          totalValue += total;
        });

        valorTotal.value = totalValue;
      }

      // Função para lidar com a entrada do código
      function handleCodigoInput(event) {
        const codigoValue = event.target.value;
        const url = `http://localhost/vendas_server.php?codigo=${codigoValue}`;

        fetch(url)
          .then(response => response.json())
          .then(data => {
            const row = event.target.parentElement.parentElement;
            if (data) {
              // Preenche os campos da linha com os dados do backend
              row.querySelector('.descricao').value = data.descricao || '';
              row.querySelector('.marca').value = data.marca || '';
              row.querySelector('.preco').value = data.preco || '';
            } else {
              // Limpa os campos se nenhum dado for encontrado
              row.querySelector('.descricao').value = '';
              row.querySelector('.marca').value = '';
              row.querySelector('.preco').value = '';
            }
          })
          .catch(error => {
            console.error('Erro:', error);
          });
      }
    });

  </script>
</body>

</html>