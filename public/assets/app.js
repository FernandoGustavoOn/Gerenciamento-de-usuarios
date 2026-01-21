document.addEventListener('DOMContentLoaded', function(){
  const apiBase = 'api.php';
  const usersTableBody = document.querySelector('#usersTable tbody');
  const btnNew = document.getElementById('btnNew');
  const modal = document.getElementById('modal');
  const modalTitle = document.getElementById('modalTitle');
  const userForm = document.getElementById('userForm');
  const btnCancel = document.getElementById('btnCancel');

  function openModal(edit=false){
    modal.setAttribute('aria-hidden','false');
    modal.style.display = 'flex';
    modalTitle.textContent = edit ? 'Editar usuário' : 'Novo usuário';
  }
  function closeModal(){
    modal.setAttribute('aria-hidden','true');
    modal.style.display = 'none';
    userForm.reset();
    document.getElementById('userId').value = '';
  }

  btnNew.addEventListener('click', ()=>{ openModal(false); });
  btnCancel.addEventListener('click', (e)=>{ e.preventDefault(); closeModal(); });

  // delegation for edit/delete
  usersTableBody.addEventListener('click', async (e)=>{
    const tr = e.target.closest('tr[data-id]');
    if(!tr) return;
    const id = tr.getAttribute('data-id');
    if(e.target.matches('.edit')){
      // populate
      document.getElementById('userId').value = id;
      document.getElementById('name').value = tr.children[1].textContent.trim();
      document.getElementById('email').value = tr.children[2].textContent.trim();
      document.getElementById('phone').value = tr.children[3].textContent.trim();
      openModal(true);
    }
    if(e.target.matches('.delete')){
      if(!confirm('Confirma exclusão deste usuário?')) return;
      try{
        const res = await fetch(`${apiBase}?action=delete`,{
          method:'POST',headers:{'Content-Type':'application/json'},
          body: JSON.stringify({id})
        });
        if(!res.ok) throw await res.json();
        tr.remove();
      }catch(err){
        alert(err && err.error ? err.error : 'Erro ao excluir');
      }
    }
  });

  userForm.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const id = document.getElementById('userId').value;
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const payload = {name,email,phone};
    const action = id ? 'update' : 'create';
    if(id) payload.id = id;
    try{
      const res = await fetch(`${apiBase}?action=${action}`,{
        method:'POST',headers:{'Content-Type':'application/json'},
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if(!res.ok){
        if(data.errors){
          alert(Object.values(data.errors).join('\n'));
        } else {
          alert(data.error || 'Erro');
        }
        return;
      }
      if(action === 'create'){
        appendRow(data);
      } else {
        replaceRow(data);
      }
      closeModal();
    }catch(err){
      alert('Erro na requisição');
    }
  });

  function appendRow(u){
    const tr = document.createElement('tr');
    tr.setAttribute('data-id', u.id);
    tr.innerHTML = `
      <td>${escapeHtml(u.id)}</td>
      <td>${escapeHtml(u.name)}</td>
      <td>${escapeHtml(u.email)}</td>
      <td>${escapeHtml(u.phone)}</td>
      <td>
        <button class="btn small edit">Editar</button>
        <button class="btn small danger delete">Excluir</button>
      </td>`;
    usersTableBody.appendChild(tr);
  }

  function replaceRow(u){
    const tr = usersTableBody.querySelector(`tr[data-id="${u.id}"]`);
    if(!tr) return appendRow(u);
    tr.children[1].textContent = u.name;
    tr.children[2].textContent = u.email;
    tr.children[3].textContent = u.phone;
  }

  function escapeHtml(str){
    if(str === null || str === undefined) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

});
