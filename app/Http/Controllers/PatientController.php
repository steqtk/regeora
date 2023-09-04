<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Http\Services\PatientService;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class PatientController extends Controller
{
    /**
     * @param PatientRequest $request
     * @return PatientResource
     */
    public function create(PatientRequest $request, PatientService $patientService): PatientResource
    {
        $patientAge = $patientService->age($request->validated('birthdate'));
        
        $patient = Patient::updateOrCreate([
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'birthdate' => Carbon::parse($request->validated('birthdate')),
            'age' => $patientAge->age,
            'age_type' => $patientAge->age_type,
        ]);
     
        $patientService->toCacheAndJob($patient);
       
        return PatientResource::make($patient);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getList(): AnonymousResourceCollection
    {
        $patients = Cache::remember('patients', 5*60, fn()=> Patient::all());
        
        return PatientResource::collection($patients);
    }
}
