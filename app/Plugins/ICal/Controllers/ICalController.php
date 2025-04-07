<?php

namespace App\Plugins\ICal\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\ICal\Models\Apartment;
use App\Plugins\ICal\Models\ApartmentAvailability;
use App\Plugins\ICal\Models\Room;
use App\Plugins\ICal\Models\RoomAvailability;
use App\Plugins\ICal\Models\Space;
use App\Plugins\ICal\Models\SpaceAvailability;
use App\Plugins\ICal\Models\Tour;
use App\Plugins\ICal\Models\TourAvailability;
use Carbon\Carbon;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\MultiDay;
use Eluceo\iCal\Domain\ValueObject\Date;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use ICal\ICal;

if (!defined('GMZPATH')) {
    exit;
}

class ICalController extends Controller
{
    private static $_inst;

    public function __construct()
    {
    }

    public function getICal($postType, $id)
    {
        require_once \ICalCore::inst()->pluginPath . '/vendor/autoload.php';

        switch ($postType) {
            case 'room':
                return $this->_getICalRoom($id);
                break;
            case 'apartment':
                return $this->_getICalApartment($id);
                break;
            case 'space':
                return $this->_getICalSpace($id);
                break;
            case 'tour':
                return $this->_getICalTour($id);
                break;
        }
        return false;
    }

    public function importIcal()
    {
        require_once \ICalCore::inst()->pluginPath . '/vendor/autoload.php';
        $services = ['room', 'apartment', 'space', 'tour'];
        foreach ($services as $service) {
            $isEnabled = is_enable_service($service);
            if ($isEnabled) {
                $func = '_importICal' . ucfirst($service);
                $this->$func();
            }
        }
        return __('Ical has been imported successfully.');
    }

    private function _importICalRoom()
    {
        $model = new Room();
        $avaiModel = new RoomAvailability();
        $iCalData = $model->getIcalsData();
        if (!$iCalData->isEmpty()) {
            foreach ($iCalData as $item) {
                $roomID = $item->id;
                $icals = maybe_unserialize($item->ical);
                $nullRoomAvail = $avaiModel->getNullRoomItem($roomID);
                if ($nullRoomAvail) {
                    $nullRoomAvail = $nullRoomAvail->toArray();
                    if ($icals) {
                        foreach ($icals as $ical) {
                            $icalUrl = $ical['url'];
                            try {
                                $icalObject = new ICal($icalUrl);
                                if ($icalObject->hasEvents()) {
                                    $events = $icalObject->events();
                                    if (!empty($events)) {
                                        foreach ($events as $event) {
                                            $start = $icalObject->iCalDateToDateTime($event->dtstart)->format('U');
                                            $end = $icalObject->iCalDateToDateTime($event->dtend)->format('U');
                                            for ($i = $start; $i < $end; $i = strtotime('+1 day', $i)) {
                                                $avaiItem = $avaiModel->getItem($roomID, $i);
                                                if (!$avaiItem) {
                                                    $dataInsert = $nullRoomAvail;
                                                    unset($dataInsert['created_at']);
                                                    unset($dataInsert['updated_at']);
                                                    $dataInsert['status'] = 'unavailable';
                                                    $dataInsert['check_in'] = $i;
                                                    $dataInsert['check_out'] = $i;
                                                    $avaiModel->create($dataInsert);
                                                }
                                            }
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                //dd($e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }

    private function _importICalApartment()
    {
        $model = new Apartment();
        $avaiModel = new ApartmentAvailability();
        $iCalData = $model->getIcalsData();
        if (!$iCalData->isEmpty()) {
            foreach ($iCalData as $item) {
                $postID = $item->id;
                $icals = maybe_unserialize($item->ical);
                if ($icals) {
                    foreach ($icals as $ical) {
                        $icalUrl = $ical['url'];
                        try {
                            $icalObject = new ICal($icalUrl);
                            if ($icalObject->hasEvents()) {
                                $events = $icalObject->events();
                                if (!empty($events)) {
                                    foreach ($events as $event) {
                                        $start = $icalObject->iCalDateToDateTime($event->dtstart)->format('U');
                                        $end = $icalObject->iCalDateToDateTime($event->dtend)->format('U');
                                        for ($i = $start; $i < $end; $i = strtotime('+1 day', $i)) {
                                            $avaiItem = $avaiModel->getItem($postID, $i);
                                            if (!$avaiItem) {
                                                $dataInsert['post_id'] = $postID;
                                                $dataInsert['check_in'] = $i;
                                                $dataInsert['check_out'] = $i;
                                                $dataInsert['price'] = $item->base_price;
                                                $dataInsert['booked'] = '0';
                                                $dataInsert['status'] = 'unavailable';
                                                $dataInsert['is_base'] = '0';
                                                $avaiModel->create($dataInsert);
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            //dd($e->getMessage());
                        }
                    }
                }
            }
        }
    }

    private function _importICalSpace()
    {
        $model = new Space();
        $avaiModel = new SpaceAvailability();
        $iCalData = $model->getIcalsData();
        if (!$iCalData->isEmpty()) {
            foreach ($iCalData as $item) {
                $postID = $item->id;
                $icals = maybe_unserialize($item->ical);
                if ($icals) {
                    foreach ($icals as $ical) {
                        $icalUrl = $ical['url'];
                        try {
                            $icalObject = new ICal($icalUrl);
                            if ($icalObject->hasEvents()) {
                                $events = $icalObject->events();
                                if (!empty($events)) {
                                    foreach ($events as $event) {
                                        $start = $icalObject->iCalDateToDateTime($event->dtstart)->format('U');
                                        $end = $icalObject->iCalDateToDateTime($event->dtend)->format('U');
                                        for ($i = $start; $i < $end; $i = strtotime('+1 day', $i)) {
                                            $avaiItem = $avaiModel->getItem($postID, $i);
                                            if (!$avaiItem) {
                                                $dataInsert['post_id'] = $postID;
                                                $dataInsert['check_in'] = $i;
                                                $dataInsert['check_out'] = $i;
                                                $dataInsert['price'] = $item->base_price;
                                                $dataInsert['booked'] = '0';
                                                $dataInsert['status'] = 'unavailable';
                                                $dataInsert['is_base'] = '0';
                                                $avaiModel->create($dataInsert);
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            //dd($e->getMessage());
                        }
                    }
                }
            }
        }
    }

    private function _importICalTour()
    {
        $model = new Tour();
        $avaiModel = new TourAvailability();
        $iCalData = $model->getIcalsData();
        if (!$iCalData->isEmpty()) {
            foreach ($iCalData as $item) {
                $postID = $item->id;
                $icals = maybe_unserialize($item->ical);
                if ($icals) {
                    foreach ($icals as $ical) {
                        $icalUrl = $ical['url'];
                        try {
                            $icalObject = new ICal($icalUrl);
                            if ($icalObject->hasEvents()) {
                                $events = $icalObject->events();
                                if (!empty($events)) {
                                    foreach ($events as $event) {
                                        $start = $icalObject->iCalDateToDateTime($event->dtstart)->format('U');
                                        $end = $icalObject->iCalDateToDateTime($event->dtend)->format('U');
                                        for ($i = $start; $i < $end; $i = strtotime('+1 day', $i)) {
                                            $avaiItem = $avaiModel->getItem($postID, $i);
                                            if (!$avaiItem) {
                                                $dataInsert['post_id'] = $postID;
                                                $dataInsert['check_in'] = $i;
                                                $dataInsert['check_out'] = $i;
                                                $dataInsert['adult_price'] = $item->adult_price;
                                                $dataInsert['children_price'] = $item->children_price;
                                                $dataInsert['infant_price'] = $item->infant_price;
                                                $dataInsert['group_size'] = $item->group_size;
                                                $dataInsert['booked'] = '0';
                                                $dataInsert['status'] = 'unavailable';
                                                $dataInsert['is_base'] = '0';
                                                $avaiModel->create($dataInsert);
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            //dd($e->getMessage());
                        }
                    }
                }
            }
        }
    }

    private function _getICalTour($id)
    {
        $model = new Tour();
        $checkExists = $model->query()->find($id);
        if ($checkExists) {
            $today = Carbon::now()->timestamp;
            $avalModel = new TourAvailability();
            $data = $avalModel->getUnavailableData($id, $today);
            $events = [];
            if (!$data->isEmpty()) {
                foreach ($data as $item) {
                    $summary = __('Unavailable');
                    if ($item['status'] !== 'unavailable') {
                        $summary = __('Booked');
                    }
                    $event = new Event();
                    $event->setSummary($summary);
                    $start = new Date(new \DateTime(date('Y-m-d', $item->check_in)));
                    $end = new Date(new \DateTime(date('Y-m-d', $item->check_out)));
                    $occurrence = new MultiDay($start, $end);
                    $event->setOccurrence($occurrence);
                    $events[] = $event;
                }
            }
            $calendar = new Calendar($events);
            $calendar->setProductIdentifier(url('/'));
            $componentFactory = new CalendarFactory();
            $calendarComponent = $componentFactory->createCalendar($calendar);
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="tour' . time() . '.ics"');
            echo $calendarComponent;
            exit();
        }
        return response()->redirectTo('/');
    }

    private function _getICalSpace($id)
    {
        $model = new Space();
        $checkExists = $model->query()->find($id);
        if ($checkExists) {
            $today = Carbon::now()->timestamp;
            $avalModel = new SpaceAvailability();
            $data = $avalModel->getUnavailableData($id, $today);
            $events = [];
            if (!$data->isEmpty()) {
                foreach ($data as $item) {
                    $summary = __('Unavailable');
                    if ($item['status'] !== 'unavailable') {
                        $summary = __('Booked');
                    }
                    $event = new Event();
                    $event->setSummary($summary);
                    $start = new Date(new \DateTime(date('Y-m-d', $item->check_in)));
                    $end = new Date(new \DateTime(date('Y-m-d', $item->check_out)));
                    $occurrence = new MultiDay($start, $end);
                    $event->setOccurrence($occurrence);
                    $events[] = $event;
                }
            }
            $calendar = new Calendar($events);
            $calendar->setProductIdentifier(url('/'));
            $componentFactory = new CalendarFactory();
            $calendarComponent = $componentFactory->createCalendar($calendar);
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="space' . time() . '.ics"');
            echo $calendarComponent;
            exit();
        }
        return response()->redirectTo('/');
    }

    private function _getICalApartment($id)
    {
        $model = new Apartment();
        $checkExists = $model->query()->find($id);
        if ($checkExists) {
            $today = Carbon::now()->timestamp;
            $avalModel = new ApartmentAvailability();
            $data = $avalModel->getUnavailableData($id, $today);
            $events = [];
            if (!$data->isEmpty()) {
                foreach ($data as $item) {
                    $summary = __('Unavailable');
                    if ($item['status'] !== 'unavailable') {
                        $summary = __('Booked');
                    }
                    $event = new Event();
                    $event->setSummary($summary);
                    $start = new Date(new \DateTime(date('Y-m-d', $item->check_in)));
                    $end = new Date(new \DateTime(date('Y-m-d', $item->check_out)));
                    $occurrence = new MultiDay($start, $end);
                    $event->setOccurrence($occurrence);
                    $events[] = $event;
                }
            }
            $calendar = new Calendar($events);
            $calendar->setProductIdentifier(url('/'));
            $componentFactory = new CalendarFactory();
            $calendarComponent = $componentFactory->createCalendar($calendar);
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="apartment' . time() . '.ics"');
            echo $calendarComponent;
            exit();
        }
        return response()->redirectTo('/');
    }

    private function _getICalRoom($id)
    {
        $model = new Room();
        $checkExists = $model->query()->find($id);
        if ($checkExists) {
            $today = Carbon::now()->timestamp;
            $avalModel = new RoomAvailability();
            $data = $avalModel->getUnavailableData($id, $today, $checkExists['hotel_id']);
            $events = [];
            if (!$data->isEmpty()) {
                foreach ($data as $item) {
                    $summary = __('Unavailable');
                    if ($item['status'] !== 'unavailable') {
                        $summary = __('Booked');
                    }
                    $event = new Event();
                    $event->setSummary($summary);
                    $start = new Date(new \DateTime(date('Y-m-d', $item->check_in)));
                    $end = new Date(new \DateTime(date('Y-m-d', $item->check_out)));
                    $occurrence = new MultiDay($start, $end);
                    $event->setOccurrence($occurrence);
                    $events[] = $event;
                }
            }
            $calendar = new Calendar($events);
            $calendar->setProductIdentifier(url('/'));
            $componentFactory = new CalendarFactory();
            $calendarComponent = $componentFactory->createCalendar($calendar);
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="room' . time() . '.ics"');
            echo $calendarComponent;
            exit();
        }
        return response()->redirectTo('/');
    }

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }
}