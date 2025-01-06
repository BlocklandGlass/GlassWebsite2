<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('components/paginator');

        if (file_exists($revPath = base_path('.rev'))) {
            $rev = file_get_contents($revPath);
            $rev = iconv('UTF-8', 'ISO-8859-1//IGNORE', $rev);
            view()->share('revision', $rev);

            $revTime = date('Y-m-d', filemtime($revPath));
            view()->share('revisionTime', $revTime);
        }

        Validator::extendImplicit('semver', function ($attribute, $value, $parameters, $validator) { // TODO: Migrate to App\Rules\... - https://laravel.com/docs/11.x/validation#custom-validation-rules
            try {
                $version = implode('.', $value);

                $regex = '/^(?>0|[1-9]\d*)\.(?>0|[1-9]\d*)\.(?>0|[1-9]\d*)(?>-([a-z-][\da-z-]+|[\da-z-]+[a-z-][\da-z-]*|0|[1-9]\d*)(\.(?>[a-z-][\da-z-]+|[\da-z-]+[a-z-][\da-z-]*|0|[1-9]\d*))*)?(?>\+[\da-z-]+(\.[\da-z-]+)*)?$/';

                return preg_match($regex, $version);
            } catch (\Throwable $e) {
                return false;
            }
        });

        Validator::extendImplicit('addon', function ($attribute, $value, $parameters, $validator) { // TODO: Migrate to App\Rules\... - https://laravel.com/docs/11.x/validation#custom-validation-rules
            try {
                $fileName = $value->getClientOriginalName();

                $regex = '/^([A-Za-z][^\s]+\_[A-Za-z0-9]+(?i)\.zip)$/';

                return preg_match($regex, $fileName);
            } catch (\Throwable $e) {
                return false;
            }
        });

        Validator::extendImplicit('defaultaddon', function ($attribute, $value, $parameters, $validator) { // TODO: Migrate to App\Rules\... - https://laravel.com/docs/11.x/validation#custom-validation-rules
            try {
                $fileName = $value->getClientOriginalName();

                $defaultAddons = [
                    'bot_blockhead.zip',
                    'bot_hole.zip',
                    'bot_horse.zip',
                    'bot_shark.zip',
                    'bot_zombie.zip',
                    'brick_arch.zip',
                    'brick_checkpoint.zip',
                    'brick_christmas_tree.zip',
                    'brick_doors.zip',
                    'brick_halloween.zip',
                    'brick_large_cubes.zip',
                    'brick_modter_4xpack.zip',
                    'brick_modter_basicpack.zip',
                    'brick_modter_invertedpack.zip',
                    'brick_poster_8x.zip',
                    'brick_teledoor.zip',
                    'brick_treasure_chest.zip',
                    'brick_v15.zip',
                    'daycycle_default.zip',
                    'decal_default.zip',
                    'decal_hoodie.zip',
                    'decal_jirue.zip',
                    'decal_worm.zip',
                    'emote_alarm.zip',
                    'emote_confusion.zip',
                    'emote_hate.zip',
                    'emote_love.zip',
                    'event_camera_control.zip',
                    'face_default.zip',
                    'face_jirue.zip',
                    'face_mythbusters.zip',
                    'gamemode_blockheads_ruin_xmas.zip',
                    'gamemode_custom.zip',
                    'gamemode_freebuild.zip',
                    'gamemode_mote_mansion.zip',
                    'gamemode_ninja_jump_challenge.zip',
                    'gamemode_pirate_dm.zip',
                    'gamemode_rampage.zip',
                    'gamemode_speedkart.zip',
                    'gamemode_tutorial.zip',
                    'gamemode_two_ship_dm.zip',
                    'ground_bedroom.zip',
                    'ground_lava.zip',
                    'ground_plate.zip',
                    'ground_tt.zip',
                    'ground_white.zip',
                    'item_key.zip',
                    'item_skis.zip',
                    'item_sports.zip',
                    'light_animated.zip',
                    'light_basic.zip',
                    'particle_basic.zip',
                    'particle_fx_cans.zip',
                    'particle_grass.zip',
                    'particle_player.zip',
                    'particle_tools.zip',
                    'player_fuel_jet.zip',
                    'player_jump_jet.zip',
                    'player_leap_jet.zip',
                    'player_no_jet.zip',
                    'player_quake.zip',
                    'print_1x2f_blpremote.zip',
                    'print_1x2f_default.zip',
                    'print_2x2f_default.zip',
                    'print_2x2r_default.zip',
                    'print_2x2r_monitor3.zip',
                    'print_letters_default.zip',
                    'print_modter_default.zip',
                    'print_poster_tutorial.zip',
                    'projectile_gravityrocket.zip',
                    'projectile_pinball.zip',
                    'projectile_pong.zip',
                    'projectile_radio_wave.zip',
                    'script_player_persistence.zip',
                    'server_vehiclegore.zip',
                    'sky_blank.zip',
                    'sky_blue2.zip',
                    'sky_halloween.zip',
                    'sky_heat2.zip',
                    'sky_limbo.zip',
                    'sky_skylands.zip',
                    'sky_slate_desert.zip',
                    'sky_slate_storm.zip',
                    'sky_spooky1.zip',
                    'sky_spooky3.zip',
                    'sky_sunset.zip',
                    'sound_beeps.zip',
                    'sound_phone.zip',
                    'sound_synth4.zip',
                    'speedkart_descent.zip',
                    'speedkart_greenhills.zip',
                    'speedkart_harbor.zip',
                    'speedkart_hydro_plant.zip',
                    'speedkart_lighthouse.zip',
                    'speedkart_north_pole.zip',
                    'speedkart_sand_castle.zip',
                    'support_doors.zip',
                    'support_legacydoors.zip',
                    'support_player_persistence.zip',
                    'vehicle_ball.zip',
                    'vehicle_flying_wheeled_jeep.zip',
                    'vehicle_horse.zip',
                    'vehicle_jeep.zip',
                    'vehicle_magic_carpet.zip',
                    'vehicle_pirate_cannon.zip',
                    'vehicle_rowboat.zip',
                    'vehicle_tank.zip',
                    'water_brick.zip',
                    'water_default.zip',
                    'water_lava.zip',
                    'water_white.zip',
                    'weapon_bow.zip',
                    'weapon_gun.zip',
                    'weapon_guns_akimbo.zip',
                    'weapon_horse_ray.zip',
                    'weapon_push_broom.zip',
                    'weapon_rocket_launcher.zip',
                    'weapon_spear.zip',
                    'weapon_sword.zip',
                ];

                if (in_array(strtolower($fileName), $defaultAddons)) {
                    return false;
                }

                return true;
            } catch (\Throwable $e) {
                return false;
            }
        });

        Validator::extendImplicit('uniqueaddon', function ($attribute, $value, $parameters, $validator) { // TODO: Migrate to App\Rules\... - https://laravel.com/docs/11.x/validation#custom-validation-rules
            try {
                $fileName = $value->getClientOriginalName();

                return ! (\App\Models\AddonUpload::where('file_name', $fileName)->exists());
            } catch (\Throwable $e) {
                return false;
            }
        });
    }
}
