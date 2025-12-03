function loadProduk() {
    fetch('../includes/produk.php?action=read')
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('daftarProduk');
            tbody.innerHTML = '';
            data.forEach(p => {
                tbody.innerHTML += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4"><img src="../assets/img/${p.gambar}" class="h-16 rounded object-cover"></td>
                    <td class="p-4 font-medium">${p.nama}</td>
                    <td class="p-4">Rp ${parseInt(p.harga).toLocaleString('id-ID')}</td>
                    <td class="p-4">
                        <button onclick="edit(${p.id})" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Edit</button>
                        <button onclick="hapus(${p.id})" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
                    </td>
                </tr>`;
            });
        });
}

function edit(id) {
    fetch(`../includes/produk.php?action=get&id=${id}`)
        .then(r => r.json())
        .then(p => {
            document.getElementById('id').value = p.id;
            document.getElementById('nama').value = p.nama;
            document.getElementById('harga').value = p.harga;
            document.getElementById('deskripsi').value = p.deskripsi || '';
            document.getElementById('bestseller').checked = p.bestseller == 1;
            document.getElementById('unik').checked = p.unik == 1;
        });
}

function hapus(id) {
    if (confirm('Yakin hapus produk ini?')) {
        fetch(`../admin/hapus_produk.php?id=${id}`)
            .then(() => loadProduk());
    }
}

document.getElementById('formProduk').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('id', document.getElementById('id').value);
    formData.append('nama', document.getElementById('nama').value);
    formData.append('harga', document.getElementById('harga').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('bestseller', document.getElementById('bestseller').checked ? 1 : 0);
    formData.append('unik', document.getElementById('unik').checked ? 1 : 0);
    if (document.getElementById('gambar').files[0]) {
        formData.append('gambar', document.getElementById('gambar').files[0]);
    }

    fetch(document.getElementById('id').value ? '../admin/edit_produk.php' : '../admin/tambah_produk.php', {
        method: 'POST',
        body: formData
    }).then(() => {
        loadProduk();
        this.reset();
        document.getElementById('id').value = '';
    });
};

loadProduk();