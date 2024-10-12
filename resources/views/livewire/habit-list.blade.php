namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Habit;

class HabitList extends Component
{
public $habits;

protected $listeners = ['habitAdded' => '$refresh'];

public function mount()
{
$this->habits = Habit::all();
}

public function deleteHabit($id)
{
$habit = Habit::find($id);
if ($habit) {
$habit->delete();
$this->habits = Habit::all();
}
}

public function render()
{
return view('livewire.habit-list');
}
}
