@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    
    <!-- Hero Section & Formulaire -->
    <div class="text-center space-y-4">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span> Service de suivi en direct
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">
            Où se trouve votre colis ?
        </h1>
        <p class="text-slate-400 text-base max-w-lg mx-auto">
            Entrez votre numéro de suivi pour consulter la position et le statut de livraison en temps réel.
        </p>
    </div>

    <!-- Carte Formulaire -->
    <div class="bg-slate-900/80 border border-slate-800 p-3 sm:p-4 rounded-2xl shadow-2xl backdrop-blur-xl">
        <form action="/" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="tracking_code" placeholder="Numéro de suivi (ex: TRK-TEST1234)" value="{{ request('tracking_code') }}" required
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-950/60 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-sm">
            </div>
            <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2">
                <span>Localiser</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

    <!-- Message d'erreur -->
    @if(isset($error))
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $error }}</span>
        </div>
    @endif

    <!-- Carte de résultat -->
    @if(isset($parcel))
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header du résultat -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-800">
                <div>
                    <span class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Code de suivi</span>
                    <h2 class="text-2xl font-mono font-bold text-indigo-400 mt-0.5">{{ $parcel['tracking_code'] }}</h2>
                </div>

                <!-- Badges de statut -->
                <div>
                    @if($parcel['status'] === 'pending')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> En attente
                        </span>
                    @elseif($parcel['status'] === 'in_transit')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                            <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span> En transit
                        </span>
                    @elseif($parcel['status'] === 'delivered')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Livré
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span> Annulé
                        </span>
                    @endif
                </div>
            </div>

            <!-- Grille d'informations -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div class="p-4 bg-slate-950/50 rounded-xl border border-slate-800/60">
                    <p class="text-xs text-slate-500 font-medium">Expéditeur</p>
                    <p class="text-white font-semibold mt-1">{{ $parcel['sender_name'] }}</p>
                </div>

                <div class="p-4 bg-slate-950/50 rounded-xl border border-slate-800/60">
                    <p class="text-xs text-slate-500 font-medium">Destinataire</p>
                    <p class="text-white font-semibold mt-1">{{ $parcel['recipient_name'] }}</p>
                </div>

                <div class="p-4 bg-slate-950/50 rounded-xl border border-slate-800/60">
                    <p class="text-xs text-slate-500 font-medium">Destination</p>
                    <p class="text-white font-semibold mt-1">{{ $parcel['destination_address'] }}</p>
                </div>

                <div class="p-4 bg-slate-950/50 rounded-xl border border-slate-800/60">
                    <p class="text-xs text-slate-500 font-medium">Téléphone de contact</p>
                    <p class="text-white font-semibold mt-1">{{ $parcel['recipient_phone'] }}</p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection