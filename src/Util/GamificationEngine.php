<?php

namespace App\Util;

use App\Entity\User;

class GamificationEngine
{
    public const EXP_THRESHOLD = [0, 300, 600, 1000, 1500, 2100, 2800, 3600, 4500, 5500];

    public function computeExperienceNeededForLevel($level)
    {
        foreach (self::EXP_THRESHOLD as $key => $value) {

            if ($key + 1 === $level) {
                return $value;
            }
        }
    }

    public function computeLevelForUser(User $user)
    {
        $xp = $user->getExperience();
        foreach (self::EXP_THRESHOLD as $key => $value) {
            if ($xp < $value) {
                return $key + 1;
            }
        }
    }

    public function computeLeveCompletionForUser(User $user)
    {
        $xp = $user->getExperience();
    }
}
