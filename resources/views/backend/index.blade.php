<!--
|--------------------------------------------------------------------------
| resources/views/backend/index.blade.php *** Copyright netprogs.pl | available only at Udemy.com | further distribution is prohibited  ***
|--------------------------------------------------------------------------
-->
@extends('layouts.backend') <!-- Lecture 5 -->

@section('content') <!-- Lecture 5 -->
<h2 class="sub-header">Booking calendar</h2>

@foreach( $objects as $o=>$object ) <!-- Lecture 29 -->

@php ( $o++ ) <!-- Lecture 29 -->
<h3 class="red">{{ $object->name /* Lecture 29 */ }} object</h3>


@foreach( $object->rooms as $r=>$room ) <!-- Lecture 29 -->

<!-- Lecture 30 -->
@push('scripts')
    <script>

        var eventDates{{ $o.$r }} = {}; /* Lecture 32 $o.$r */
        var datesConfirmed{{ $o.$r }} = []; /* Lecture 32 */
        var datesnotConfirmed{{ $o.$r }} = [];/* Lecture 32 */

        /* Lecture 32 */
        @foreach($room->reservations as $reservation)

        @if ($reservation->status)
        datesConfirmed{{$o.$r}}.push(datesBetween(new Date('{{$reservation->day_in}}'), new Date('{{$reservation->day_out}}')));
        @else
        datesnotConfirmed{{$o.$r}}.push(datesBetween(new Date('{{$reservation->day_in}}'), new Date('{{$reservation->day_out}}')));
        @endif

            @endforeach

            datesConfirmed{{$o.$r}} = [].concat.apply([], datesConfirmed{{$o.$r}}); /* Lecture 32 */
        datesnotConfirmed{{$o.$r}} = [].concat.apply([], datesnotConfirmed{{$o.$r}}); /* Lecture 32 */


        for (var i = 0; i < datesConfirmed{{ $o.$r }}.length; i++) /* Lecture 32 $o.$r */
        {
            eventDates{{ $o.$r }}[ datesConfirmed{{ $o.$r }}[i] ] = 'confirmed'; /* Lecture 32 $o.$r */
        }

        var tmp{{ $o.$r }} = {}; /* Lecture 32 $o.$r */
        for (var i = 0; i < datesnotConfirmed{{ $o.$r }}.length; i++) /* Lecture 32 $o.$r */
        {
            tmp{{ $o.$r }}[ datesnotConfirmed{{ $o.$r }}[i] ] = 'notconfirmed'; /* Lecture 32 $o.$r */
        }


        Object.assign(eventDates{{ $o.$r }}, tmp{{ $o.$r }});  /* Lecture 32 $o.$r */


        $(function () {
            $(".reservation_calendar" + {{ $o.$r }}/* Lecture 32 */).datepicker({
                onSelect: function (date/* Lecture 32 data->date */) {

                    $('.hidden_' + {{ $o.$r }}).hide(); /* Lecture 32 $o.$r */
                    $('.loader_' + {{ $o.$r }}).show(); /* Lecture 32 $o.$r */

                    App.GetReservationData({{ $room->id }}, {{ $o.$r }}, date ); /* Lecture 32 */

                },
                beforeShowDay: function (date)
                {
                    var tmp = eventDates{{ $o.$r }}[ $.datepicker.formatDate('mm/dd/yy', date)]; /* Lecture 32 $o.$r */
                    //            console.log(tmp);
                    if (tmp)
                    {
                        if (tmp == 'confirmed')
                            return [true, 'reservationconfirmed'];
                        else
                            return [true, 'reservationnotconfirmed'];
                    } else
                        return [false, ''];

                }


            });
        });


    </script>
@endpush

<h4 class="blue"> Room {{ $room->room_number /* Lecture 29 */ }}</h4>

<div class="row top-buffer">
    <div class="col-md-3">
        <div class="reservation_calendar{{ $o.$r/* Lecture 29 */}}"></div>
    </div>
    <div class="col-md-9">
        <div class="center-block loader loader_{{ $o.$r /* Lecture 29 */}}" style="display: none;"></div>
        <div class="hidden_{{ $o.$r /* Lecture 29 */}}" style="display: none;">


            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Room number</th>
                        <th>Check in</th>
                        <th>Check out</th>
                        <th>Guest</th>

                        @if( Auth::user()->hasRole(['admin','owner']) )
                            <th>Confirmation</th>
                        @endif

                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="reservation_data_room_number"></td>
                        <td class="reservation_data_day_in"></td>
                        <td class="reservation_data_day_out"></td>
                        <td><a class="reservation_data_person" target="_blank" href=""></a></td>

                        @if( Auth::user()->hasRole(['admin','owner']) )
                            <td><a href="#" class="btn btn-primary btn-xs reservation_data_confirm_reservation keep_pos">Confirm</a></td>
                        @endif

                        <td><a class="btn btn-danger btn-xs reservation_data_delete_reservation keep_pos" href=""><span class="glyphicon glyphicon-remove"></span></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<hr>

@endforeach

@endforeach
@endsection


