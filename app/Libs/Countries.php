<?php

namespace App\Libs;
use App\Models\Country;
use App\Models\State;

class Countries
{
    public function getAllCountries($id = 0, $name = 'b_country',$class="form-control") {
        $countries = Country::All();
        ob_start();

        if ($name=='s_') $name="s_country";
        if ($id == 0)
            $id=231;

        ?>

        <select class="<?= $class ?>" name="<?= $name ?>" placeholder="Country" id="<?= $name ?>" required>
            <option value="0"></option>
            <?php foreach ($countries as $country) { ?>
                <option <?php echo $country->id==$id  ? 'selected' : '' ?> value="<?= $country->id ?>"><?= $country->name ?></option>
            <?php } ?>
        </select>


        <?php

        $content = ob_get_clean();
        return $content;

    }

    public function getCountriesWithSortname($sortname = 0, $name = 'b_country',$class="form-control") {
        $countries = Country::All();
        ob_start();

        if ($name=='s_') $name="s_country";
        if ($sortname == 0)
            $sortname="US";

        ?>

        <select class="<?= $class ?>" name="<?= $name ?>" placeholder="Country" id="<?= $name ?>" required >
            <option value="0"></option>
            <?php foreach ($countries as $country) { ?>
                <option <?php echo $country->sortname==$sortname  ? 'selected' : '' ?> value="<?= $country->sortname ?>"><?= $country->name ?></option>
            <?php } ?>
        </select>


        <?php

        $content = ob_get_clean();
        return $content;

    }

    public function getAllStates($id = 0,$prefix='b_',$country_id=231,$class="form-control") {
        $states = State::where('country_id',$country_id)->get();
        ob_start();

        ?>
        <select wire:model.live="selectedState" class="<?= $class ?>" placeholder="State" name="<?= $prefix ?>state" id="<?= $prefix ?>state-input" required>
            <option value=""></option>
            <?php foreach ($states as $state) { ?>
                <option value="<?= $state->id ?>"<?php echo $id==$state->id ? 'selected' : '' ?>><?= $state->name ?></option>
            <?php } ?>
        </select>


        <?php

        $content = ob_get_clean();
        return $content;

    }

    public function getCountry($id) {
        $country = Country::find($id);
        if (!$country)
            return '';

        return $country->name;
    }

    public function getCountryIdByName($name) {
        $country = Country::where('name',$name)->first();
        if (!$country)
            return '';

        return $country->id;
    }

    public function getCountryIdBySortname($sortname) {
        $country = Country::where('sortname',$sortname)->first();
        if (!$country)
            return '';

        return $country->id;
    }

    public function getCountryBySortname($sortname) {
        $country = Country::where('sortname',$sortname)->first();
        if (!$country)
            return '';

        return $country->name;
    }

    public function getStateFromCountry($id) {
        $state = State::find($id);
        if (!$state) return '';

        return $state->name;
    }

    public function getStateCodeFromCountry($id) {
        $state = State::find($id);
        if (!$state) return '';

        if ($state->code)
            return $state->code;
        else
            return $this->getStateFromCountry($id);

    }

    public function getSortnameFromCountryId($id) {
        $sortname = Country::find($id);

        if ($sortname->sortname)
            return $sortname->sortname;

        return '';

    }

    public function getStateNameById($id) {
        $state = State::find($id);

        if ($state)
            return $state->name;

        return '';

    }
    public function getStateIdByName($name) {
        $state = State::where('name', $name)->first();

        if ($state->name)
            return $state->id;

        return '';

    }

    public function getStateByCode($code) {
        if (!$code) return '';

        $state = State::where('code',$code)->first();

        if ($state)
            return $state->id;
        else
            return $this->getStateFromCountry($code);

    }
}
