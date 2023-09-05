<?php

namespace App\Http\Services;

use App\Jobs\PatientJob;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PatientService
{
    public int $age;
    public string $age_type;

    public function age($birthdate): self
    {
        $time = Carbon::now()->diff($birthdate);
        
        if ($time->y !==0){
            $this->age = $time->y;
            $this->age_type = 'год';
            
            return $this;
        }
        
        if ($time->m !== 0){
            $this->age = $time->m;
            $this->age_type = 'месяц';

            return $this;
        }

        if ($time->d !== 0){
            $this->age = $time->d;
            $this->age_type = 'день';

            return $this;
        }
    }

    public function toCacheAndJob(Patient $patient)
    {
        Cache::add($patient->name, $patient, 5*60);
        PatientJob::dispatch($patient);
    }

}