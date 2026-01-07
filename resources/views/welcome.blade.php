<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --primary: #f16363; --secondary: #ec4899; --bg: #f5f7ff; --text: #1e293b; }
        body { background-color: var(--bg); font-family: 'Inter', sans-serif; color: var(--text); margin: 0; }
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logo { font-size: 1.5rem; font-weight: 800; color: var(--primary); }
        
        /* Grid */
        .contacts-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .contact-card { background: white; padding: 20px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .avatar { width: 45px; height: 45px; border-radius: 12px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        /* Buttons */
        .btn { padding: 10px 20px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; }
        .btn-add { background: var(--primary); color: white; }
        .btn-edit { color: var(--primary); background: #eef2ff; padding: 8px; border-radius: 8px; border:none; cursor:pointer;}
        .btn-delete { color: #ef4444; background: #fef2f2; padding: 8px; border-radius: 8px; border:none; cursor:pointer;}

        /* Modal - Corrigé pour être toujours visible quand x-show est vrai */
        .modal-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); display: flex; align-items: center; 
            justify-content: center; z-index: 9999; 
        }
        .modal-content { background: white; padding: 30px; border-radius: 24px; width: 90%; max-width: 400px; position: relative; }
        .form-input { width: 100%; padding: 12px; margin: 8px 0; border: 2px solid #f1f5f9; border-radius: 12px; box-sizing: border-box; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ 
    openModal: false, 
    editMode: false, 
    currentContact: { id: '', nom: '', num: '' } 
}" x-cloak>

    <div class="container">
        <header>
            <div class="logo"><i class="fas fa-bolt"></i> Contact Manager</div>
            <button class="btn btn-add" @click="editMode = false; currentContact = {id:'', nom:'', num:''}; openModal = true">
                <i class="fas fa-plus"></i> Add a new Contact
            </button>
        </header>

        <div class="contacts-grid">
            @foreach($contacts as $contact)
            <div class="contact-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="avatar">{{ strtoupper(substr($contact->nom, 0, 1)) }}</div>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn-edit" @click="currentContact = { id: '{{$contact->id}}', nom: '{{ addslashes($contact->nom) }}', num: '{{$contact->num}}' }; editMode = true; openModal = true;">
                            <i class="fas fa-pen"></i>
                        </button>

                        <form action="/delete/{{ $contact->id }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div style="margin-top: 15px;">
                    <h3 style="margin: 0;">{{ $contact->nom }}</h3>
                    <p style="color: #64748b;"><i class="fas fa-phone"></i> {{ $contact->num }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="modal-overlay" x-show="openModal" style="display: none;" x-transition>
        <div class="modal-content" @click.away="openModal = false">
            <h2 x-text="editMode ? 'Modifier le contact' : 'Ajouter un contact'"></h2>
            
            <form :action="editMode ? '/update/' + currentContact.id : '/add'" method="POST">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div style="margin-top: 15px;">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-input" x-model="currentContact.nom" required>
                </div>

                <div style="margin-top: 15px;">
                    <label>Téléphone</label>
                    <input type="text" name="num" class="form-input" x-model="currentContact.num" required>
                </div>

                <div style="margin-top: 25px; display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-add" style="flex: 1;" x-text="editMode ? 'Mettre à jour' : 'Enregistrer'"></button>
                    <button type="button" class="btn" style="background: #f1f5f9;" @click="openModal = false">Annuler</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>