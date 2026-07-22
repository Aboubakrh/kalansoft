<flux:navlist.item icon="squares-2x2" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">Dashboard</flux:navlist.item>
<flux:navlist.item icon="users" href="{{ route('admin.eleves.index') }}" :current="request()->routeIs('admin.eleves.*')">Élèves</flux:navlist.item>
<flux:navlist.item icon="book-open" href="{{ route('admin.professeurs.index') }}" :current="request()->routeIs('admin.professeurs.*')">Professeurs</flux:navlist.item>
<flux:navlist.group expandable heading="Scolarité" class="mt-2" :expanded="request()->routeIs('admin.classes.*') || request()->routeIs('admin.salles.*') || request()->routeIs('admin.emplois-du-temps.*')">
    <flux:navlist.item icon="user-group" href="{{ route('admin.classes.index') }}" :current="request()->routeIs('admin.classes.*')">Classes</flux:navlist.item>
    <flux:navlist.item icon="building-office-2" href="{{ route('admin.salles.index') }}" :current="request()->routeIs('admin.salles.*')">Salles</flux:navlist.item>
    <flux:navlist.item icon="calendar" href="{{ route('admin.emplois-du-temps.index') }}" :current="request()->routeIs('admin.emplois-du-temps.*')">Emplois du temps</flux:navlist.item>
</flux:navlist.group>
<flux:navlist.group expandable heading="Pédagogie" class="mt-2" :expanded="request()->routeIs('admin.matieres.*') || request()->routeIs('admin.cours.*') || request()->routeIs('admin.cahier-texte.*') || request()->routeIs('admin.coefficients.*') || request()->routeIs('admin.evaluations.*') || request()->routeIs('admin.notes.*') || request()->routeIs('admin.bulletins.*')">
    <flux:navlist.item icon="book-open" href="{{ route('admin.matieres.index') }}" :current="request()->routeIs('admin.matieres.*')">Matières</flux:navlist.item>
    <flux:navlist.item icon="academic-cap" href="{{ route('admin.cours.index') }}" :current="request()->routeIs('admin.cours.*')">Cours</flux:navlist.item>
    <flux:navlist.item icon="book-open" href="{{ route('admin.cahier-texte.index') }}" :current="request()->routeIs('admin.cahier-texte.*')">Cahier de Texte</flux:navlist.item>
    <flux:navlist.item icon="rectangle-stack" href="{{ route('admin.coefficients.index') }}" :current="request()->routeIs('admin.coefficients.*')">Coefficients</flux:navlist.item>
    <flux:navlist.item icon="document-check" href="{{ route('admin.evaluations.index') }}" :current="request()->routeIs('admin.evaluations.*') || request()->routeIs('admin.notes.*')">Notes & Évaluations</flux:navlist.item>
    <flux:navlist.item icon="document-duplicate" href="{{ route('admin.bulletins.index') }}" :current="request()->routeIs('admin.bulletins.*')">Bulletins</flux:navlist.item>
</flux:navlist.group>

<flux:navlist.group expandable heading="Ressources Humaines" class="mt-2" :expanded="request()->routeIs('admin.rh.*')">
    <flux:navlist.item icon="users" href="{{ route('admin.rh.index') }}" :current="request()->routeIs('admin.rh.index')">Personnel</flux:navlist.item>
    <flux:navlist.item icon="clock" href="{{ route('admin.rh.pointage') }}" :current="request()->routeIs('admin.rh.pointage')">Pointage du personnel</flux:navlist.item>
    <flux:navlist.item icon="calculator" href="{{ route('admin.rh.vacations') }}" :current="request()->routeIs('admin.rh.vacations')">Vacations</flux:navlist.item>
</flux:navlist.group>
<flux:navlist.group expandable heading="Finances" class="mt-2" :expanded="request()->routeIs('admin.finances.*')">
    <flux:navlist.item icon="banknotes" href="{{ route('admin.finances.paiements') }}" :current="request()->routeIs('admin.finances.paiements')">Paiements</flux:navlist.item>
    <flux:navlist.item icon="currency-dollar" href="{{ route('admin.finances.encaisser') }}" :current="request()->routeIs('admin.finances.encaisser')">Encaisser</flux:navlist.item>
    <flux:navlist.item icon="wallet" href="{{ route('admin.finances.frais') }}" :current="request()->routeIs('admin.finances.frais')">Frais de Scolarité</flux:navlist.item>
</flux:navlist.group>

<flux:navlist.group expandable heading="Paramètres" class="mt-2" :expanded="request()->routeIs('admin.settings.*')">
    <flux:navlist.item icon="cog-6-tooth" href="{{ route('admin.settings.index') }}" :current="request()->routeIs('admin.settings.index')">Général</flux:navlist.item>
    <flux:navlist.item icon="calendar" href="{{ route('admin.settings.annees') }}" :current="request()->routeIs('admin.settings.annees')">Années Scolaires</flux:navlist.item>
    <flux:navlist.item icon="rectangle-stack" href="{{ route('admin.settings.series') }}" :current="request()->routeIs('admin.settings.series')">Séries</flux:navlist.item>
</flux:navlist.group>
