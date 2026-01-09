## 🗃️ Dump de la table `pokemon_move`

L’import des attaques Pokémon (`pokemon_move`) depuis la PokéAPI est long (≈ 600 000 lignes, ~1h).
Pour éviter de relancer cet import à chaque installation, le projet utilise un **dump SQL versionné**.

---

### 🎯 Objectifs

- Éviter les imports PokéAPI répétitifs
- Garantir des données identiques sur tous les environnements (WSL, macOS…)
- Réduire drastiquement le temps de setup

---

### 📁 Emplacement du dump

```bash
database/dumps/pokemon_move.sql
```

Le dump contient :

- la structure de la table
- les données
- la gestion des clés étrangères

---

### 📤 Générer le dump (si nécessaire)

À faire uniquement si les données ou la structure changent.

```bash
docker ps
```

```bash
docker exec shinydex-db \
  mysqldump -u root -p shinydex pokemon_move \
  > database/dumps/pokemon_move.sql
```

```bash
git add database/dumps/pokemon_move.sql
git commit -m "chore(db): update pokemon_move dump"
```

---

### 📥 Restaurer le dump

```bash
docker exec -i mysql \
  mysql -u root -p shinydex \
  < dumps/pokemon_move.sql
```

---

### ⏱️ Temps d’exécution

| Méthode           | Durée        |
| ----------------- | ------------ |
| Import PokéAPI    | ~1 heure     |
| Restauration dump | < 5 secondes |

---

### ⚠️ Notes importantes

- Le dump est destiné au développement uniquement

- À regénérer uniquement si :

  - la structure de pokemon_move change

  - la logique d’import PokéAPI est modifiée

- Le dump gère temporairement les clés étrangères :

```sql
SET FOREIGN_KEY_CHECKS=0;
...
SET FOREIGN_KEY_CHECKS=1;
```

---

### 🧠 Bonnes pratiques

- ✅ Utiliser le dump pour le développement

- ❌ Ne pas modifier le dump manuellement

- ❌ Ne pas automatiser sa régénération
