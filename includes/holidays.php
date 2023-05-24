<?php




class Holidays
{
    /**
     * Google API Key.
     *
     * @var string
     */
    private $api_key;

    /**
     * Country Code.
     *
     * @var string
     */
    private $country_code;


    /**
     * Language.
     *
     * @var string
     */
    private $language;

    /**
     * Start Date.
     *
     * @var string
     */
    private $start_date;

    /**
     * End Date.
     *
     * @var string
     */
    private $end_date;

    /**
     * Minimal output boolean.
     *
     * @var bool
     */
    private $minimal = false;

    /**
     * Dates only output boolean.
     *
     * @var bool
     */
    private $dates_only = false;

    /**
     * Construct!
     *
     * @return void
     */
    public function __construct()
    {
        $this->start_date = date('Y-m-d') . 'T00:00:00-00:00';
        $this->end_date = (date('Y') + 1) . '-01-01T00:00:00-00:00';
    }

    /**
     * Setting starting date.
     *
     * @param string Date in any format
     *
     * @return self
     */
    public function from($str)
    {
        $this->start_date = date('Y-m-d', strtotime($str)) . 'T00:00:00-00:00';

        return $this;
    }

    /**
     * Setting end date.
     *
     * @param string Date in any format
     *
     * @return self
     */
    public function to($str)
    {
        $this->end_date = date('Y-m-d', strtotime($str)) . 'T00:00:00-00:00';

        return $this;
    }

    /**
     * Setter of API key.
     *
     * @return self
     */
    public function withApiKey($api_key)
    {
        $this->api_key = $api_key;

        return $this;
    }

    /**
     * Define the country code to retrieve holidays for.
     *
     * @return self
     */
    public function inCountry($country_code, $language)
    {
        $this->country_code = strtolower($country_code);
        $this->language = strtolower($language);

        return $this;
    }

    /**
     * Define the output result as minimal.
     *
     * @return self
     */
    public function withMinimalOutput()
    {
        $this->minimal = true;

        return $this;
    }

    /**
     * Define the output as dates only.
     *
     * @return self
     */
    public function withDatesOnly()
    {
        $this->dates_only = true;

        return $this;
    }

    /**
     * Get the list of holidays.
     *
     * @return mixed
     */
    public function lists()
    {
        if (!$this->api_key) {
            throw new \Exception('Providing an API key might be a better start. RTFM.');
        }

        if (!$this->country_code) {
            throw new \Exception('Providing a Country Code is a good idea. RTFM.');
        }

        $result = array();

        $api_url = "https://www.googleapis.com/calendar/v3/calendars/" . $this->language . "." . $this->country_code . "%23holiday%40group.v.calendar.google.com/events" .
            '?singleEvents=false' .
            "&timeMax=" . $this->end_date .
            "&timeMin=" . $this->start_date .
            "&key=" . $this->api_key;
        $response = json_decode(file_get_contents($api_url), true);

        if (isset($response['items'])) {
            if ($this->dates_only === true) {
                foreach ($response['items'] as $holiday) {
                    $result[] = $holiday['start']['date'];
                }

                sort($result);
            } elseif ($this->minimal === true) {
                foreach ($response['items'] as $holiday) {
                    $result[] = [
                        'name' => $holiday['summary'],
                        'date' => $holiday['start']['date'],
                    ];
                }

                usort($result, function ($a, $b) {
                    if ($a['date'] == $b['date']) {
                        return 0;
                    }

                    return ($a['date'] < $b['date']) ? -1 : 1;
                });
            } else {
                $result = [];
                $response = $response['items'];
                usort($response, function ($a, $b) {
                    if ($a['start']['date'] == $b['start']['date']) {
                        return 0;
                    }
                    return ($a['start']['date'] < $b['start']['date']) ? -1 : 1;
                });
                foreach ($response as $holiday) {
                    $DateDebut = $holiday['start']['date'];
                    $DateFin = $holiday['end']['date'];
                    $datediff = strtotime($DateFin) - strtotime($DateDebut);
                    $index =  $this->exits($result, $holiday['summary']);
                    if (!$index) {
                        $result[] = [
                            'Titre' => $holiday['summary'],
                            'Annee' => date('Y', strtotime($DateDebut)),
                            'DateDebut' => $DateDebut,
                            'DateFin' => $DateDebut,
                            'NbJours' => $datediff / (60 * 60 * 24)
                        ];
                    } else {
                        $result[$index - 1]['DateFin'] = $DateDebut;
                        $result[$index - 1]['NbJours'] = $result[$index - 1]['NbJours'] + 1;
                    }
                }

                
            }
        }

        return $result;
    }

    public function dates()
    {
        $dates = array();
        $hoildays =  $this->lists();
        foreach ($hoildays as $key => $item) {
            $dates[$item['DateDebut']] = $item['Titre'];
            $dates[$item['DateFin']] =  $item['Titre'];
        }
        return $dates;
    }



    function exits($array, $value)
    {
        foreach ($array as $key => $item) {
            if ($item['Titre'] == $value) return $key + 1;
        }
        return false;
    }
}
