@foreach($risks as $key => $vehicle)
    <h3 class="label label-primary">{{ $code }} {{ $key + 1 }}</h3>
    <div class="row">
        <input type="hidden" name="vehicles[{{ $key }}][id]" value="{{ encode($vehicle->id) }}">
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Tipo de Vehículo</label>
                {!! SelectField::input('vehicles[' . $key . '][vehicle_type]', $data['vehicle_types']->toArray(), [
                    'class' => 'form-control disabled-vehicle',
                    ], old('vehicle_type', $vehicle->ad_vehicle_type_id))
                !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Categoria</label>
                {!! SelectField::input('vehicles[' . $key . '][category]', $data['vehicle_categories']->toArray(), [
                    'class' => 'form-control disabled-vehicle',
                    ], old('category', $vehicle->category->category))
                !!}
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Año</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Año"
                       name="vehicles[{{ $key }}][year]" value="{{ $vehicle->year }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Marca</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Marca"
                       name="vehicles[{{ $key }}][brand]" value="{{ $vehicle->brand }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Color</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Color"
                       name="vehicles[{{ $key }}][color]" value="{{ $vehicle->color }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Modelo</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Modelo"
                       name="vehicles[{{ $key }}][model]" value="{{ $vehicle->model }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Plaza circulacion</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Plaza circulacion"
                       name="vehicles[{{ $key }}][circulation_plaza]" value="{{ $vehicle->circulation_plaza }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Desplazamiento</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Desplazamiento"
                       name="vehicles[{{ $key }}][displacement]" value="{{ $vehicle->displacement }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Origen</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Origen"
                       name="vehicles[{{ $key }}][origin]" value="{{ $vehicle->origin }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Placa</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Placa"
                       name="vehicles[{{ $key }}][license_plate]" value="{{ $vehicle->license_plate }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Uso de Vehículo</label>
                {!! SelectField::input('vehicles[' . $key . '][use]', $data['vehicle_uses']->toArray(), [
                    'class' => 'form-control disabled-vehicle',
                    ], old('use', $vehicle->use))
                !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group text-center">
                <label class="label label-default">Cero Kilómetros</label>
                <select name="vehicles[{{ $key }}][mileage]" class="form-control disabled-vehicle">
                    <option value="">Seleccione</option>
                    <option value="1" {{ $vehicle->mileage ? 'selected' : '' }}>SI</option>
                    <option value="0" {{ ! $vehicle->mileage ? 'selected' : '' }}>NO</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Motor</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Motor"
                       name="vehicles[{{ $key }}][engine]" value="{{ $vehicle->engine }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Chasis</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Chasis"
                       name="vehicles[{{ $key }}][chassis]" value="{{ $vehicle->chassis }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Cap/Ton</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Cap/Ton"
                       name="vehicles[{{ $key }}][tonnage_capacity]" value="{{ $vehicle->tonnage_capacity }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">N° Asientos</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="N° Asientos"
                       name="vehicles[{{ $key }}][seat_number]" value="{{ $vehicle->seat_number }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Valor Comercial</label>
                <input type="text" class="form-control disabled-vehicle" placeholder="Valor Comercial"
                       name="vehicles[{{ $key }}][insured_value]" value="{{ $vehicle->insured_value }}">
            </div>
        </div>
    </div>
    <hr>
@endforeach