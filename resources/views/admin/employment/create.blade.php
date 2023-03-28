@extends('layouts.app', ['title' => 'Tambah Santri - Admin'])

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-300">
    <div class="container mx-auto px-6 py-8">

        <div class="p-6 bg-white rounded-md shadow-md">
            <h2 class="text-lg text-gray-700 font-semibold capitalize">Tambah Santri</h2>
            <hr class="mt-4">
            <form action="{{ route('admin.employment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6 mt-4">
                    
                    <div>
                        <label class="text-gray-700" for="name">Nama Santri</label>
                        <input class="form-input w-full mt-1 rounded-md bg-gray-200 focus:bg-white" type="text" name="name" 
                        value="{{ old('name') }}" placeholder="Nama Santri">
                        @error('name')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-1">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-700" for="name">Username</label>
                        <input class="form-input w-full mt-1 rounded-md bg-gray-200 focus:bg-white" type="text" name="username" 
                        value="{{ old('username') }}" placeholder="Username">
                        @error('username')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-1">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label class="text-gray-700" for="name">Kantor</label>
                        <select class="w-full border  mt-1 bg-gray-200 focus:bg-white rounded px-3 py-2 outline-none" name="office_id">
                            @foreach($offices as $office)
                                <option class="py-1" value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                        @error('office_id')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-1">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror
                    </div>


                    <div>
                        <label class="text-gray-700" for="name">Jabatan</label>
                        <select class="w-full border  mt-1 bg-gray-200 focus:bg-white rounded px-3 py-2 outline-none" name="jabatan">
                            <option class="py-1" value="Teller">Teller</option>
                            <option class="py-1" value="Marketing">Marketing</option>
                            <option class="py-1" value="Branch Manager">Branch Manager</option>
                            <option class="py-1" value="Staff">Staff</option>
                            <option class="py-1" value="Asdirut">Asdirut</option>
                            <option class="py-1" value="Direktur">Direktur</option>
                            <option class="py-1" value="Security">Security</option>
                        </select>
                        @error('jabatan')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-1">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-gray-700" for="name">Password</label>
                        <input class="form-input w-full mt-1 rounded-md bg-gray-200 focus:bg-white" type="password" name="password" 
                        value="{{ old('password') }}" placeholder="password">
                        @error('password')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-1">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-start mt-4">
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-gray-200 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700">Simpan</button>
                </div>
            </form>
        </div>
        
    </div>
</main>
@endsection