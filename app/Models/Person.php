<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['created_by', 'first_name', 'last_name', 'birth_name', 'middle_names', 'date_of_birth'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parents()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'child_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'parent_id', 'child_id');
    }
    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    // Add the getDegreeWith method here
    public function getDegreeWith($target_person_id)
    {
        // Stop search after 25 degrees of separation
        $max_degree = 25;

        // Initialize the queue for BFS and visited array
        $queue = collect([['person_id' => $this->id, 'degree' => 0]]);
        $visited = collect([$this->id]);

        while ($queue->isNotEmpty()) {
            $current = $queue->shift();
            $current_person_id = $current['person_id'];
            $current_degree = $current['degree'];

            // If we reached the target person, return the degree
            if ($current_person_id == $target_person_id) {
                return $current_degree;
            }

            // Fetch related people (children, parents, etc.)
            $related_people = DB::table('relationships')
                                ->where('person1_id', $current_person_id)
                                ->orWhere('person2_id', $current_person_id)
                                ->get();

            // Explore all relationships from the current person
            foreach ($related_people as $relation) {
                $next_person_id = ($relation->person1_id == $current_person_id) ? $relation->person2_id : $relation->person1_id;

                // Avoid cycles by checking if the person has been visited
                if (!$visited->contains($next_person_id)) {
                    $visited->push($next_person_id);
                    $queue->push(['person_id' => $next_person_id, 'degree' => $current_degree + 1]);

                    // Stop search if degree exceeds max limit
                    if ($current_degree + 1 > $max_degree) {
                        return false;
                    }
                }
            }
        }

        return false; // If no path found
    }
}
