<?php
namespace App\Http\Controllers\Phase20;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    // Strictly non-secret UI/operations preferences. Never store API keys here.
    public const FIELDS = [
        'general.company_label' => ['label'=>'Workspace label','type'=>'string','default'=>'PostGenius Agent'],
        'general.support_email' => ['label'=>'Support email','type'=>'email','default'=>''],
        'general.default_timezone' => ['label'=>'Default display timezone','type'=>'timezone','default'=>'UTC'],
        'general.maintenance_notice' => ['label'=>'Maintenance notice','type'=>'string','default'=>''],
        'notifications.admin_digest_enabled' => ['label'=>'Admin digest preference','type'=>'boolean','default'=>false],
    ];

    public function index()
    {
        $saved = SystemSetting::whereIn('key',array_keys(self::FIELDS))->pluck('value','key');
        $fields = collect(self::FIELDS)->map(function($definition,$key) use ($saved) {
            $definition['key']=$key;
            $definition['value']=$saved->has($key) ? $saved->get($key) : $definition['default'];
            return $definition;
        });
        return view('phase20.settings',compact('fields'));
    }

    public function update(Request $request)
    {
        $data=$request->validate([
            'company_label'=>['required','string','max:100'],
            'support_email'=>['nullable','email','max:254'],
            'default_timezone'=>['required','timezone'],
            'maintenance_notice'=>['nullable','string','max:500'],
            'admin_digest_enabled'=>['sometimes','boolean'],
        ]);
        $values=[
            'general.company_label'=>$data['company_label'],
            'general.support_email'=>$data['support_email']??'',
            'general.default_timezone'=>$data['default_timezone'],
            'general.maintenance_notice'=>$data['maintenance_notice']??'',
            'notifications.admin_digest_enabled'=>($data['admin_digest_enabled']??false)?'1':'0',
        ];
        DB::transaction(function() use ($values) {
            foreach($values as $key=>$value) {
                SystemSetting::updateOrCreate(['key'=>$key],[
                    'group'=>explode('.',$key)[0],
                    'value'=>$value,
                    'type'=>self::FIELDS[$key]['type'],
                    'is_public'=>false,
                ]);
            }
        });
        return redirect()->route('admin.system-settings.index')->with('success','Settings saved.');
    }
}
