<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\PropertyMetadata;

    class PropertyMetadataController extends Controller {
        public function update(Request $request) {
            $request->validate([
                'property_id' => 'required',
                'tag' => 'nullable|string',
                'status' => 'nullable|string',
                'note' => 'nullable|string',
            ]);

            PropertyMetadata::updateOrCreate(
                ['property_id' => $request->property_id],
                [
                    'tag' => $request->tag,
                    'status' => $request->status,
                    'note' => $request->note,
                ]
            );

            return redirect()->back()->with('success', 'Property metadata updated successfully.');
        }
    }
