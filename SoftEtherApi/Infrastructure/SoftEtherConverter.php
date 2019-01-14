<?php

namespace SoftEtherApi\Infrastructure
{
    class SoftEtherConverter
    {
        public static function DateTimeToSoftEtherLong(\DateTime $date = null)
        {
            if($date == null)
                return null;

            $softEtherDate = $date;
            $softEtherDate->add(new \DateInterval("PT".$date->getOffset()."H"));
            $softEtherDate->sub(new \DateInterval("PT9H")); //sub 9 Hours from UTC for JAPAN Timezone which SoftEther expects
            return $softEtherDate->getTimestamp() * 1000;
        }

        public static function SoftEtherLongToDateTime($date = null)
        {
            if($date == null)
                return null;

            $result = new \DateTime();
            $result->setTimestamp($date / 1000);
            $result->add(new \DateInterval("PT9H"));//add 9 Hours for JAPAN Timezone which SoftEther delivers to UTC
            return $result;
        }
    }
}