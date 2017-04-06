<?php

/*
 * Author: Jinandra
 * Date: 21-01-2017
 * Bundle Builder Controller
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB,
    Auth,
    Mail,
    Input;
use App\Models\Ucode;
use App\Models\Media;
use App\Models\BundleCart;
use App\Models\Collection;
use Services_Twilio;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Log;
use Symfony\Component\HttpFoundation\Session\Session;

class BundleController extends Controller {

    /**
     * GET /bundle
     * Displays user's ucode list and its media (stats)
     */
    public function index (Request $request) {

        $session = new Session();

        // Values handling using session after ucode created successfully ( Ucode Popup )
        if ($session->get("pdfloadingcount") != "" && $session->get("pdfloadingcount") > 1) {
            $session->remove('pdfloadingcount');
            $session->remove('pdfgenerate');
        }
        if ($session->get("pdfgenerate") != "") {
            $pdfgeneratesession = $session->get("pdfgenerate");
            $pdfloadingcount = $session->get("pdfloadingcount");
            $pdfloadingcount = $pdfloadingcount + 1;
            $session->set("pdfloadingcount", $pdfloadingcount);
        }

        // ucode search text
        $search = Ucode::normalize($request->query('s'));
        $ucodes = Auth::user()->statsUcodes($search);

        $medias = "";
        if ( count($ucodes) > 0 ) {
          $medias = Ucode::find($ucodes[0]->id)->statsMedia();
          $ucode = $ucodes[0]->ucode;
        } else {
          $ucode = "";
        }

        return response()
          ->view('beta.bundle.home', [
            's' => $request->query('s'),
            'ucodes' => $ucodes,
            'medias' => $medias,
            'ucode' => $ucode,
            'session' => $session,
            'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
            'collections' => $this->getCollections()
          ])
          ->header('Cache-Control', $this->noCacheControlHeader());
    }

    /**
     * GET /bundle/view
     * Display current bundle cart
     */
    public function cartView () {
        $countBundleCart = BundleCart::getBundleCartCount(Auth::user()->id);

        //UCode generation and checking whether us=code has been used previously or not
        do {
          $ucode = ucodeGenerate();
          $found = !is_null(Ucode::findByUcode($ucode));
        } while ( $found );

        $result = BundleCart::getBundleCartList(Auth::user()->id);

        return view('beta.bundle.index', ['media' => $result, 'ucode' => $ucode, 'countBundleCart' => $countBundleCart]);
    }

    /**
     * GET /bundle/{id}/add
     * Store a media to bundle cart
     * @param int $id id media
     */
    public function cartStore(Request $request, $id) {

        $result = BundleCart::isInBundle($id);
        $condition = false;
        if ($result) {
            $condition = true;
            BundleCart::deleteMedia($id);
        }

        // If the media has not been input then INSERT media into bundle_cart
        if (!$condition) {
            BundleCart::addMedia($id);
        }

        if ($request->ajax()) {
            $media = Media::findSingle($id);
            return response()->json([
                        'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
                        'alert' => $condition,
                        'title' => array_fetch($media, 'title')
            ]);
        }
        return back();
    }

    /**
     * GET /bundle/{ucode}/addUcode
     * Add all media in ucode to bundle cart
     * Won't duplicate the media
     * @param string $ucode
     */
    public function addUcode($ucode) {
      $colsMedia = Ucode::findByUcode($ucode)->listMediaNotInBundle();
      if ($colsMedia) {
        foreach ($colsMedia as $medium) {
          BundleCart::addMedia($medium->id_media);
        }
      }

      return response()->json([
        'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
        'ucodeStatusBundle' => Ucode::getStatusBundle(),
        'title' => $ucode
      ]);
    }

    /**
     * GET /bundle/{ucode}/removeUcode
     * Remove media in bundle cart that match with media in ucode
     * @param string $ucode
     */
    public function removeUcode ($ucode) {
      $colsMedia = Ucode::findByUcode($ucode)->details();
      $mediaToDelete = array();
      if ($colsMedia) {
        foreach ($colsMedia as $medium) {
          array_push($mediaToDelete, $medium->id_media);
        }
      }
      DB::table('bundle_cart')
        ->where('user_id', Auth::user()->id)
        ->whereIn('media_id', $mediaToDelete)
        ->delete();
      return response()->json([
        'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
        'ucodeStatusBundle' => Ucode::getStatusBundle(),
        'title' => $ucode
      ]);
    }

    /**
     * DELETE /bundle
     * This method delete ucode and its content
     * @param Array $ucodes array of [ucode_id' => ['media_id', 'media_id']]
     */
    public function deleteUcodes(Request $request) {
        $ucodes = $request->input('ucodes');
        
        foreach ($ucodes as $ucodesId => $media) {
            switch ($ucodesId) {
                case 'ucodeMedia':
                    break;
                default:
                    if (in_array('self', $media)) {
                      Ucode::where('ucode', $ucodesId)->delete();
                    }
            }
        }
        return back();
    }

    /**
     * POST /bundle/cart/store
     * Create new ucode
     */
    public function ucodeStore(Request $request) {

        $result = BundleCart::getBundleCartList(Auth::user()->id);

        //Check media in bundle
        if ($result) {
            $pdfdata = array();
            //Double check the ucode, If the ucode already exist will generate a new one
            $ucode = $request->ucode2;
            $found = !is_null(Ucode::findByUcode($ucode));
            while ( $found ) {
              $ucode = ucodeGenerate();
              $found = !is_null(Ucode::findByUcode($ucode));
            }

            //Save Ucode
            $uc = new Ucode;
            $uc->ucode   = $ucode;
            $uc->user_id = Auth::user()->id;
            $uc->save();

            // Copy from user's bundle cart to ucode details
            foreach (Auth::user()->bundles as $row) {
              $uc->media()->attach($row->media_id, ['sort_order' => $row->sort_order]);
            }
            Auth::user()->emptyBundleCart();  // empty bundle cart

            //Email request
            if (!empty($request->email_ucode)) {
                $emailTo = $request->email_ucode;
                $pdf = $this->_generatePDF($uc->id);
                Mail::send('emails.ucode', ['ucode' => $ucode], function ($m) use($emailTo, $ucode, $pdf) {
                    $m->from('info@enfolink.com', 'Enfolink');
                    $m->to($emailTo)->subject("Your UCode");
                    $m->attachData($pdf->output(), "UCode {$ucode}.pdf");
                });
            }
            //Text Request
            if (!empty($request->text_ucode)) {
                //Twilio
                $account_sid = "ACd50aa3587b715291ff109897b1f145d1";
                $auth_token = "5dd870a19445145e33a8386740a0885c";

                $client = new Services_Twilio($account_sid, $auth_token);
                $sms = $client->account->messages->sendMessage(
                        "8133080215", //sender
                        $request->text_ucode, //destination
                        //"Your UCode is " . $ucode . " Please access it at http://enfolink.org/ucode?ucode=" . $ucode
                        "To access your health information, go here (http://enfolink.org/ucode?ucode=" . $ucode . ") or go to enfolink.org and type " . $ucode . ". Please do not reply."
                );
            }

            $sessionObj = new Session();

            if ($request->pdfgenerate == 1) {
                $pdfdata['pdfgenerateval'] = $request->get('pdfgenerate');
                $pdfdata['ucodegenerateval'] = $ucode;
                $pdfdata['ucodeidval'] = $uc->id;
                $pdfloadingcount = 1;
                $sessionObj->set("pdfgenerate", $pdfdata);
                $sessionObj->set("pdfloadingcount", $pdfloadingcount);
            }

            return redirect('/bundle')->with('newUcode', $ucode);
        } else {
            return redirect('/bundle/view')->with('error_message', 'Please add media to your bundle builder before you can create a UCode.');
        }
    }

    /**
     * GET /bundle/cart/{id}/delete
     * Delete media from bundle_cart
     * @param int $id id media
     */
    public function cartDelete($id) {
      BundleCart::deleteMedia($id);
      return back();
    }

    /**
     * GET /bundle/cart/delete
     * Delete user's bundle cart
     */
    public function cartDeleteAll() {
      Auth::user()->emptyBundleCart();
      return back();
    }

    /**
     * GET /bundle/ucode/{ucode}
     * Displays ucode's media (and its statistic)
     * @param string $ucode a ucode
     */
    public function viewUCode ($ucode) {
      $object = Ucode::findByUcode($ucode);
      return view('beta.partials.ucode.mediaList', [
        'media' => $object->statsMedia(),
        'ucode' => $ucode
      ]);
    }

    /**
     * GET /bundle/mediasortorder
     * Author: Jinandra
     * Date: 10-27-2016
     * Sorting for media in bundle builder by drag and drop
     * @param string $sort_string
     *
     * @return \Illuminate\Http\Response
     */
    public function mediaSortInBundleBuilder() {

        // get the list of bundle builder cart id separated by comma (,)
        $stringBundleBuilderCartId = Input::get("bundle_builder_cart_id");

        if ($stringBundleBuilderCartId != "") {
            // convert the string list to an array
            $arrBundleBuilderCartId = explode(',', $stringBundleBuilderCartId);
            $i = 1;
            foreach ($arrBundleBuilderCartId as $bundleBuilderCartId) {
              BundleCart::where('id', $bundleBuilderCartId)->update(['sort_order' => $i]);
              $i++;
            }
        }

        return back();
    }

    /**
     * GET /bundle/ucodepdf
     * Author: Jinandra
     * Date: 19-12-2016
     * Generate PDF from Ucode Page
     * @param string $ucode_id
     *
     * @return \Illuminate\Http\Response
     */
    public function ucodePdfGenerate() {
      $ucode = Ucode::find(Input::get('ucodeid'));
      $data = [
        'ucodedetails' => $ucode,
        'medias' => $ucode->statsMedia()
      ];
      return view('beta.bundle.ucodepdf', $data);
    }

    /**
     * GET /bundle/ucodepdfdownload/{ucodeid}
     * Author: Jinandra
     * Date: 28-12-2016
     * Download PDF from Ucode Page
     * @param string $ucodeId
     */
    public function ucodeDownloadPdf ($ucodeId) {
      $pdf = $this->_generatePDF($ucodeId);
      return $pdf->stream();
    }

    /**
     * Author: Jinandra
     * Date: 06-02-2017
     * PATCH /, action: sort, move, copy
     * @param array $request
     */
    public function patchCollections(Request $request) {
        switch ($request->input('action')) {
            case 'copy':
                return $this->_copy($request);
                break;
            default:
                if ($request->ajax()) {
                    return response()->json([ 'error' => 'invalid action']);
                }
                return back();
        }
    }

    /**
     * GET /bundle/ucodecopyclipboard
     * Author: Jinandra
     * Date: 21-02-2017
     * Ucode copy to clipboard
     * @param string $ucode_id
     *
     * @return \Illuminate\Http\Response
     */
    public function ucodeCopytoClipboard() {
        $data = array();
        $ucodeId = Input::get("ucodeid");
        $ucodeDetails = Ucode::find($ucodeId);
        $data['ucodedetails'] = $ucodeDetails;

        $medias = $ucodeDetails->statsMedia();
        $data['medias'] = $medias;

        return view('beta.bundle.ucodecopy', $data);
    }



    /**
     * Instantiate pdf object for given ucode id
     * @param int $ucodeId ucode id
     * @return PDF pdf object
     */
    private function _generatePDF ($ucodeId) {
      $ucode = Ucode::find($ucodeId);
      $data = [
        'ucodedetails' => $ucode,
        'medias' => $ucode->statsMedia()
      ];
      $pdf = PDF::loadView('beta.bundle.ucodepdfdownload', $data);
      $pdf->setPaper('a4', 'portrait');
      return $pdf;
    }

    /**
     * Author: Jinandra
     * Date: 06-02-2017
     * Copy ucode media in folders
     *
     * @return \Illuminate\Http\Response
     */
    private function _copy(Request $request) {
        $targets = $request->input('targets');
        $media = $request->input('media');
        if (!is_null($targets)) {
            if (!is_null($media)) {
                Collection::copyMedia($media, $targets);
            }
        }
        if ($request->ajax()) {
            return response()->json(['media' => $media, 'targets' => $targets]);
        }
        $redirectUrl = $request->input('redirectURL');
        if (!is_null($redirectUrl)) {
            return redirect($redirectUrl);
        }
        return back();
    }
}
