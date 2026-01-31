# KarryCar - Riepilogo Sviluppo

## Stato Iniziale

Progetto Laravel 12 base con:

- Autenticazione (login, registrazione, recupero password)
- Two-Factor Authentication
- Pagine impostazioni utente (profilo, password, tema)
- Modelli vuoti: User, Team, Shipment, Referent

---

## Stato Attuale

Applicazione per la gestione spedizioni con sistema referenti.

---

## Implementazioni

### Gestione Spedizioni

- Lista spedizioni con paginazione
- Pagina dettaglio spedizione
- Filtro per team (multi-tenant)

### Sistema Referenti

- Referenti associabili alle spedizioni con ruolo **partenza** o **arrivo**
- Creazione referente al volo durante l'associazione
- Selezione da referenti esistenti del team
- Modali per aggiunta, visualizzazione e rimozione

### Integrita' Dati

- Soft deletes su Referent, Shipment, Team
- Validazione: referente e spedizione devono appartenere allo stesso team
- Email referente obbligatoria

### Script Pulizia Dati

Comando `php artisan app:patch-referents-data` per:

1. **Deduplicare referenti** - Unifica referenti con stessa email+team, sposta le associazioni al record principale, elimina (soft) i duplicati

2. **Rimuovere associazioni invalide** - Elimina collegamenti referente-spedizione dove i team non corrispondono

---

## Stack Tecnico

- Laravel 12 + Inertia.js v2 + Vue 3
- Tailwind CSS v4
- TypeScript per il frontend
- PHPStan + Rector + Pint per quality code
