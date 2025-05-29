<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\PropertyMetadata;
    use App\Models\GoogleProperty;
    use Illuminate\Support\Facades\Auth;

    class PropertyMetadataController extends Controller {
        public function update(Request $request) {
            $request->validate([
                'property_id' => 'required',
                'tag' => 'nullable|string',
                'status' => 'nullable|string',
                'note' => 'nullable|string',
            ]);

            $property = GoogleProperty::where('ga_property_id', $request->property_id)->first();

            if (!$property) {
                return redirect()->back()->with('error', 'Property not found.');
            }

            $updated = PropertyMetadata::updateOrCreate(
                ['google_property_id' => $property->id],
                [
                    'tag' => $request->tag,
                    'status' => $request->status,
                    'note' => $request->note,
                    'user_id' => Auth::id(),
                ]
            );

            if ($updated) {
                return redirect()->back()->with([
                    'success' => 'Property metadata updated and saved successfully.',
                    'expanded_property' => $request->property_id,
                ]);
            } else {
                return redirect()->back()->with('error', 'Failed to update metadata.');
            } 
        }
    }
