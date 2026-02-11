<x-app-layout>

    <style>
        /* Optimisation des images pour qu'elles soient nettes */
        .img-sharp { image-rendering: -webkit-optimize-contrast; object-fit: cover; width: 100%; height: 100%; }

        /* Grille principale 50/50 pour éviter le vide à droite */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 4px solid #1e40af;
            border-radius: 2rem;
            background: white;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Styles des boutons pour qu'ils soient enfin visibles et beaux */
        .btn-admin { background-color: #2563eb !important; color: white !important; font-weight: 900; text-transform: uppercase; padding: 1rem 2rem; border-radius: 1rem; transition: all 0.2s; }
        .btn-admin:hover { background-color: #1e40af !important; transform: scale(1.02); }

        .btn-reserve { background-color: #111827 !important; color: white !important; font-weight: 900; text-transform: uppercase; width: 100%; padding: 1.25rem; border-radius: 1.5rem; letter-spacing: 1px; }

        /* Badge de prix sur l'image */
        .price-tag { background: #facc15; color: #1e40af; font-weight: 900; padding: 6px 14px; border-radius: 10px; position: absolute; bottom: 15px; left: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

        /* Tableau propre */
        .res-table { width: 100%; border-collapse: collapse; }
        .res-table th { background: #1e40af; color: white; text-transform: uppercase; font-size: 0.75rem; padding: 1.2rem; text-align: left; }
        .res-table td { padding: 1.2rem; border-bottom: 1px solid #f3f4f6; font-weight: 700; font-size: 0.85rem; }
    </style>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full border-2 border-blue-100 overflow-hidden bg-white flex items-center justify-center p-1 shadow-inner">
                    </div>
                    <div>

                        <p class="text-sm font-bold text-blue-600 italic">Antananarivo - {{ \Carbon\Carbon::now('Indian/Antananarivo')->translatedFormat('H:i') }}</p>
                    </div>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
            <div class="bg-white p-8 rounded-[2.5rem] shadow-lg border border-gray-100 mb-8">
                <h2 class="text-xs font-black text-blue-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-600 rounded-full"></span> Ajouter un nouveau plat au menu
                </h2>
                <form action="{{ route('meals.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    @csrf
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase ml-1">Nom du plat</label>
                        <input type="text" name="name" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold focus:ring-blue-500" placeholder="Ex: Riz au Poulet..." required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase ml-1">Prix (Ar)</label>
                        <select name="price" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-black text-blue-800">
                            <option value="3000">3 000 Ar</option>
                            <option value="4000">4 000 Ar</option>
                            <option value="5000">5 000 Ar</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase ml-1">Stock</label>
                        <input type="number" name="stock" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold text-center" value="20">
                    </div>
                    <div class="md:col-span-4 flex items-center gap-4">
                        <div class="flex-1">
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase ml-1">Image</label>
                            <input type="file" name="image" class="text-[10px] font-bold text-gray-400">
                        </div>
                        <button type="submit" class="btn-admin shadow-xl">Enregistrer</button>
                    </div>
                </form>
            </div>
            @endif

            <div class="main-grid">

                <div class="p-10 border-r-4 border-blue-800 bg-white">
                    <h2 class="text-xl font-black uppercase text-gray-800 mb-10 italic flex items-center gap-3">
                        Menu du jour
                    </h2>

                    <div class="space-y-12">
                        @forelse($meals as $meal)
                        <div class="flex flex-col">
                            <div class="relative aspect-video rounded-[2.5rem] overflow-hidden border-4 border-gray-900 shadow-xl">
                                @if($meal->image)
                                    <img src="{{ asset('storage/' . $meal->image) }}" class="img-sharp">
                                @endif
                                <div class="price-tag">{{ number_format($meal->price, 0, '.', ' ') }} Ar</div>
                            </div>

                            <div class="mt-6 px-2 flex justify-between items-center">
                                <h4 class="font-black text-gray-900 uppercase text-xl tracking-tight">{{ $meal->name }}</h4>
                                <span class="bg-gray-100 px-4 py-1.5 rounded-xl text-[10px] font-black text-gray-500 uppercase">Restant: {{ $meal->stock }}</span>
                            </div>

                            @if($meal->stock > 0)
                        <form action="{{ route('reservations.store') }}" method="POST" class="mt-6">
                            @csrf
                            <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                                <button class="mt-4 w-full bg-[#2563eb] hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform active:scale-95 uppercase tracking-wider text-sm">
                                    Réserver ce plat
                                </button>
                        </form>
                    @else
    <button class="mt-6 w-full py-5 rounded-2xl font-black text-sm uppercase bg-gray-300 text-gray-500 cursor-not-allowed" disabled>
        Épuisé
    </button>
@endif

                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('meals.destroy', $meal->id) }}" method="POST" class="mt-4 text-center">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[10px] font-black text-red-500 uppercase hover:underline tracking-widest">
                                        [ Supprimer du menu ]
                                    </button>
                                </form>
                            @endif
                        </div>
                        @empty
                        <div class="py-20 text-center">
                            <p class="text-gray-300 font-black uppercase italic tracking-widest text-xs">Aucun plat disponible pour le moment</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-50 flex flex-col">
                    <div class="p-8 border-b-2 border-gray-100 flex justify-between items-center bg-white">
                        <h2 class="font-black uppercase italic text-blue-900 tracking-tight">
                            {{ Auth::user()->role === 'admin' ? 'Toutes les Réservations' : 'Mes Réservations' }}
                        </h2>
                        @if(Auth::user()->role === 'admin')
                            <form action="{{ route('reservations.clear') }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="bg-red-600 text-white text-[10px] font-black px-5 py-2.5 rounded-xl uppercase shadow-md hover:bg-red-700">Vider la liste</button>
                            </form>
                        @endif
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        <table class="res-table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Plat</th>
                                    <th class="text-right">Heure</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($reservations as $res)
                                <tr class="bg-white hover:bg-blue-50/50 transition-colors">
                                    <td class="uppercase italic text-gray-700">{{ $res->user->name }}</td>
                                    <td class="text-blue-700 font-black italic uppercase">{{ $res->meal->name ?? '---' }}</td>
                                    <td class="text-right font-mono text-gray-400 italic">
                                        {{ $res->created_at->timezone('Indian/Antananarivo')->format('H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-20 text-center text-gray-300 italic font-black uppercase text-xs">Aucune donnée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> </div>
    </div>
</x-app-layout>
