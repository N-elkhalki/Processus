document.addEventListener('DOMContentLoaded', function() {
    // Gestion des étapes du formulaire de mise à jour
    const etatSelect = document.getElementById('etat_avancement');
    const typeContratSelect = document.getElementById('type_contrat');
    const autreContratField = document.getElementById('autre_contrat_field');
    
    // Fonction pour afficher le champ correspondant à l'étape sélectionnée
    function toggleEtapeFields() {
        if (!etatSelect) return;
        
        // Masquer tous les champs d'étape
        const etapeFields = document.querySelectorAll('.etape-field');
        etapeFields.forEach(field => {
            field.style.display = 'none';
        });
        
        // Afficher le champ correspondant à l'étape sélectionnée
        const selectedValue = etatSelect.value;
        const selectedField = document.getElementById(selectedValue + '_fields');
        
        if (selectedField) {
            selectedField.style.display = 'block';
            
            // Animation d'apparition
            selectedField.style.opacity = '0';
            setTimeout(() => {
                selectedField.style.opacity = '1';
                selectedField.style.transition = 'opacity 0.3s ease';
            }, 10);
        }
    }
    
    // Gestion du champ "Autre" pour le type de contrat
    function toggleAutreContrat() {
        if (!typeContratSelect || !autreContratField) return;
        
        const selectedValue = typeContratSelect.value;
        autreContratField.style.display = selectedValue === 'AUTRE' ? 'block' : 'none';
        
        if (selectedValue === 'AUTRE') {
            // Animation d'apparition
            autreContratField.style.opacity = '0';
            setTimeout(() => {
                autreContratField.style.opacity = '1';
                autreContratField.style.transition = 'opacity 0.3s ease';
            }, 10);
        }
    }
    
    // Event listeners
    if (etatSelect) {
        etatSelect.addEventListener('change', toggleEtapeFields);
        // Afficher le champ par défaut au chargement de la page
        toggleEtapeFields();
    }
    
    if (typeContratSelect) {
        typeContratSelect.addEventListener('change', toggleAutreContrat);
        // Vérifier l'état initial
        toggleAutreContrat();
    }
    
    // Filtres de recherche pour la liste des demandes
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterTable() {
        if (!searchInput || !statusFilter) return;
        
        const searchText = searchInput.value.toLowerCase();
        const filterStatus = statusFilter.value;
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        
        tableRows.forEach(row => {
            const textContent = row.textContent.toLowerCase();
            const statusCell = row.querySelector('.status-badge');
            const statusClass = statusCell ? statusCell.className : '';
            const matchSearch = searchText === '' || textContent.includes(searchText);
            const matchStatus = filterStatus === '' || statusClass.includes('status-' + filterStatus);
            
            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterTable);
    }
    
    // Animations pour les cartes de fonctionnalités
    const featureCards = document.querySelectorAll('.feature-card');
    
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.boxShadow = '0 12px 20px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
        });
    });
    
    // Animation pour les boutons
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Afficher/masquer le menu mobile
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuToggle && navLinks) {
        mobileMenuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('show');
            
            if (navLinks.classList.contains('show')) {
                navLinks.style.display = 'flex';
                navLinks.style.flexDirection = 'column';
                navLinks.style.position = 'absolute';
                navLinks.style.top = '60px';
                navLinks.style.left = '0';
                navLinks.style.right = '0';
                navLinks.style.backgroundColor = 'white';
                navLinks.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
                navLinks.style.padding = '10px';
            } else {
                navLinks.style.display = '';
            }
        });
    }
    
    // Ajout d'une animation de flash pour les alertes
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 1s ease';
            alert.style.opacity = '0.8';
            
            setTimeout(() => {
                alert.style.opacity = '1';
            }, 200);
        }, 100);
        
        // Auto-dismiss des alertes après 5 secondes
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 1000);
        }, 5000);
    });
});